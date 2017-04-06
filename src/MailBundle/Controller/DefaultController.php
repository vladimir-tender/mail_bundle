<?php

namespace MailBundle\Controller;

use MailBundle\Entity\Letter;
use MailBundle\Form\LetterType;
use MailBundle\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function countNewLettersAction()
    {
        $newLetters = $this->getDoctrine()->getRepository("MailBundle:Letter")
            ->findBy(['type' => 'inbox', 'status' => '0'], []);

        $newLetters = count($newLetters);

        if (!is_null($newLetters) && $newLetters > 0) {
            $newLetters = "<b>(" . $newLetters . ")</b>";
        }

        $response = new Response();
        $response->setContent($newLetters);

        return $response;
    }

    /**
     * @Template()
     */
    public function indexAction()
    {

    }

    /**
     * @Template()
     */
    public function inboxAction()
    {
        $inboxLetters = $this->getDoctrine()->getRepository("MailBundle:Letter")
            ->findBy(['type' => 'inbox'], ['senttime' => 'desc']);


        return [
            "letters" => $inboxLetters,

        ];
    }

    /**
     * @Template()
     */
    public function outboxAction()
    {
        $outboxLetters = $this->getDoctrine()->getRepository("MailBundle:Letter")
            ->findBy(['type' => 'outbox'], ['senttime' => 'desc']);
        return [
            "letters" => $outboxLetters
        ];
    }

    /**
     * @Template()
     */
    public function deletedAction()
    {
        $deletedLetters = $this->getDoctrine()->getRepository("MailBundle:Letter")
            ->findBy(['type' => 'deleted'], ['senttime' => 'desc']);
        return [
            "letters" => $deletedLetters
        ];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request)
    {
        $letter = new Letter();
        $form = $this->createForm(LetterType::class, $letter);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);





            $validator = $this->get('validator');
            $errors = $validator->validate($letter);

            if (count($errors) > 0) {

                return [
                    "form" => $form->createView()
                ];
            }

            $manager = $this->getDoctrine()->getManager();
            $letter->setSenttime(new \DateTime("now"));
            $letter->setType("inbox");
            $manager->persist($letter);
            $manager->flush();

            var_dump($letter->getId());

            /**
             * @var FileUploader $uploader
             */
            $uploader = $this->get("file_uploader");
            $answer = $uploader->uploaderInit($_FILES['files'], $request->get("fileuploader-list-files"), $letter);

            $letter->setFiles($answer);
            $manager->persist($letter);
            $manager->flush();

            $letterOut = clone $letter;
            $letterOut->setType("outbox");
            $manager->persist($letterOut);
            $manager->flush();

            $this->addFlash("success", "Message sent");


            return $this->redirectToRoute("mail_inbox");
        }

        return [
            'form' => $form->createView(),
        ];

    }

    public function deleteAction($id)
    {
        $letter = $this->getDoctrine()->getRepository("MailBundle:Letter")->findOneBy(["id" => $id]);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($letter);
        $letter->setType("deleted");
        $manager->flush();

        return $this->redirectToRoute("mail_inbox");
    }

    /**
     * @Template()
     */
    public function letterAction($id)
    {
        $letter = $this->getDoctrine()->getRepository("MailBundle:Letter")->findOneBy(["id" => $id]);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($letter);
        $letter->setStatus(1);
        $manager->flush();
        return [
            "letter" => $letter,
        ];
    }

    public function deleteAtAllAction($id)
    {
        $letter = $this->getDoctrine()->getRepository("MailBundle:Letter")->findOneBy(["id" => $id]);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($letter);
        $manager->flush();

        return $this->redirectToRoute("mail_inbox");
    }

    public function downloadFileAction($fileId)
    {
        $file = $this->getDoctrine()->getRepository("MailBundle:Files")
            ->findOneBy(['id' => $fileId],[]);

        $path = $this->getParameter("image_upload_dir");
        $fullFileName = $path . $file->getServerFileName();
        $clientFileName = $file->getClientFileName();

        return $this->file($fullFileName, $clientFileName);

    }
}
