<?php

namespace PauloAK\NfseLajeado\Helpers;

use DOMDocument;
use DOMElement;
use DOMNode;
use NFePHP\Common\Certificate;
use Dotenv\Dotenv;

class Signer {

    const CANONICAL = [true,false,null,null];

    private ?Certificate $certificate;

    function __construct($certPath, $certPass)
    {
        $this->certificate = Certificate::readPfx(file_get_contents($certPath), $certPass);
    }

    public function sign($xml, $tagname, $rootname = null, $mark = 'Id')
    {
        $xml = str_replace('<?xml version="1.0"?>', '', $xml);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml);
        $root = $dom->documentElement;

        if (!empty($rootname)) {
            $root = $dom->getElementsByTagName($rootname)->item(0);
        }

        $node = $dom->getElementsByTagName($tagname)->item(0);

        $dom = $this->createSignature(
            $this->certificate,
            $dom,
            $root,
            $node,
            $mark
        );

        return $dom->saveXML($dom->documentElement, LIBXML_NOXMLDECL);
    }

    private function createSignature(
        Certificate $certificate,
        DOMDocument $dom,
        DOMNode $root,
        DOMElement $node,
        $mark,
        $algorithm = OPENSSL_ALGO_SHA1,
        $canonical = self::CANONICAL,
        $options = []
    ) {
        $nsDSIG = 'http://www.w3.org/2000/09/xmldsig#';
        $nsCannonMethod = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        $nsSignatureMethod = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
        $nsDigestMethod = 'http://www.w3.org/2000/09/xmldsig#sha1';
        $digestAlgorithm = 'sha1';
        if ($algorithm == OPENSSL_ALGO_SHA256) {
            $digestAlgorithm = 'sha256';
            $nsSignatureMethod = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
            $nsDigestMethod = 'http://www.w3.org/2001/04/xmlenc#sha256';
        }
        $nsTransformMethod1 = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
        $nsTransformMethod2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        $idSigned = trim($node->getAttribute($mark));
        $digestValue = self::makeDigest($node, $digestAlgorithm, $canonical);
        $signatureNode = $dom->createElementNS($nsDSIG, 'Signature');
        $root->appendChild($signatureNode);
        $signedInfoNode = $dom->createElement('SignedInfo');
        $signatureNode->appendChild($signedInfoNode);
        $canonicalNode = $dom->createElement('CanonicalizationMethod');
        $signedInfoNode->appendChild($canonicalNode);
        $canonicalNode->setAttribute('Algorithm', $nsCannonMethod);
        $signatureMethodNode = $dom->createElement('SignatureMethod');
        $signedInfoNode->appendChild($signatureMethodNode);
        $signatureMethodNode->setAttribute('Algorithm', $nsSignatureMethod);
        $referenceNode = $dom->createElement('Reference');
        $signedInfoNode->appendChild($referenceNode);
        if (!empty($idSigned)) {
            $idSigned = "#$idSigned";
        }
        $referenceNode->setAttribute('URI', $idSigned);
        $transformsNode = $dom->createElement('Transforms');
        $referenceNode->appendChild($transformsNode);
        $transfNode1 = $dom->createElement('Transform');
        $transformsNode->appendChild($transfNode1);
        $transfNode1->setAttribute('Algorithm', $nsTransformMethod1);
        $transfNode2 = $dom->createElement('Transform');
        $transformsNode->appendChild($transfNode2);
        $transfNode2->setAttribute('Algorithm', $nsTransformMethod2);
        $digestMethodNode = $dom->createElement('DigestMethod');
        $referenceNode->appendChild($digestMethodNode);
        $digestMethodNode->setAttribute('Algorithm', $nsDigestMethod);
        $digestValueNode = $dom->createElement('DigestValue', $digestValue);
        $referenceNode->appendChild($digestValueNode);
        $c14n = self::canonize($signedInfoNode, $canonical);
        $signature = $certificate->sign($c14n, $algorithm);
        $signatureValue = base64_encode($signature);
        $signatureValueNode = $dom->createElement('SignatureValue', $signatureValue);
        $signatureNode->appendChild($signatureValueNode);
        $keyInfoNode = $dom->createElement('KeyInfo');
        $signatureNode->appendChild($keyInfoNode);
        $x509DataNode = $dom->createElement('X509Data');
        $keyInfoNode->appendChild($x509DataNode);

        if (!empty($options["subjectName"])) {
            $subjectName = $certificate->publicKey->subjectNameValue;
            $x509SubjectNode = $dom->createElement('X509SubjectName', $subjectName);
            $x509DataNode->appendChild($x509SubjectNode);
        }

        $pubKeyClean = $certificate->publicKey->unFormated();
        $x509CertificateNode = $dom->createElement('X509Certificate', $pubKeyClean);
        $x509DataNode->appendChild($x509CertificateNode);
        return $dom;
    }

    private static function makeDigest(DOMNode $node, $algorithm, $canonical = self::CANONICAL)
    {
        //calcular o hash dos dados
        $c14n = self::canonize($node, $canonical);
        $hashValue = hash($algorithm, $c14n, true);
        return base64_encode($hashValue);
    }

    private static function canonize(DOMNode $node, $canonical = self::CANONICAL)
    {
        return $node->C14N(
            $canonical[0],
            $canonical[1],
            $canonical[2],
            $canonical[3]
        );
    }
}