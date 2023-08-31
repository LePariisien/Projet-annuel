<?php 
namespace App\Controller;

use App\Entity\Files;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    #[Route('/file', name: 'app_file')]
    public function uploadFile(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        if ($request->isMethod('POST')) {
            $uploadedFile = $request->files->get('file');

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

                // Obtenir le format du fichier en utilisant pathinfo
                $fileInfo = pathinfo($newFilename);
                $format = $fileInfo['extension'];

                $uploadDirectory = $this->getParameter('upload_directory');
                $uploadedFile->move($uploadDirectory, $newFilename);

                // Obtenir la taille du fichier en utilisant la fonction filesize()
                $tempFilePath = $uploadDirectory.'/'.$newFilename;
                $size = filesize($tempFilePath);

                // Récupérer l'utilisateur actuellement authentifié
                $user = $security->getUser();

                // Obtenir le format du fichier
                $format = $uploadedFile->getClientOriginalExtension();

                // Créez une nouvelle instance de l'entité Files
                $file = new Files();
                $file->setFileName($newFilename);
                $file->setSize($size); // Définissez la taille du fichier
                $file->setUser($user);
                $file->setFormat($format); // Définir le format du fichier
                // Définissez d'autres attributs si nécessaire...

                // Persistez l'entité dans la base de données
                $entityManager->persist($file);
                $entityManager->flush();

                // Ajouter un message flash de succès
                $this->addFlash('success', 'Le fichier a été téléchargé avec succès.');

                return $this->redirectToRoute('app_file');
            }
        }
        // Fetch the list of uploaded files
        $user = $security->getUser();
        $uploadedFiles = $entityManager->getRepository(Files::class)->findBy(['user' => $user]);;

        $totalSizeBytes = 0;

        foreach ($uploadedFiles as $file) {
            $totalSizeBytes += $file->getSize();
        }

        $totalSizeGigabytes = $totalSizeBytes / (1024 * 1024 * 1024); // Convert to gigabytes

        // Pass the uploaded files to the template
        return $this->render('file/index.html.twig', [
            'uploadedFiles' => $uploadedFiles,
            'totalSizeGigabytes' => $totalSizeGigabytes,
        ]);
    }
}
