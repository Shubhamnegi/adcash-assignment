<?php


namespace App\Utility;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class JsonHelper
{
    public static function toJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
        ];

        $normalizers = [new DateTimeNormalizer(), new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext)];

        $serializer = new Serializer($normalizers, [$encoders]);
        $jsonContent = $serializer->serialize($object, 'json');

        return json_decode($jsonContent);
    }
}