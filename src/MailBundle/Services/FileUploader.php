<?php

namespace MailBundle\Services;


class FileUploader
{
    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function uploaderInit($serverFiles, $fileList)
    {
        $answer = "";
        $clientFiles = json_decode($fileList);

        $resultFileNames = array_intersect($clientFiles, $serverFiles['name']);

        $resultFilesArray = [];
        foreach ($resultFileNames as $fileName) {
            foreach ($serverFiles['name'] as $key => $name) {
                if ($fileName == $name) {
                    $resultFilesArray[$key]['name'] = $fileName;
                    $resultFilesArray[$key]['type'] = $serverFiles['type'][$key];
                    $resultFilesArray[$key]['tmp_name'] = $serverFiles['tmp_name'][$key];
                    $resultFilesArray[$key]['error'] = $serverFiles['error'][$key];
                    $resultFilesArray[$key]['size'] = $serverFiles['size'][$key];
                }
            }
        }

        var_dump($resultFilesArray);

        return $answer;
    }


}