<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Form\LibroType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LibroController extends AbstractController{
    //RUTA LISTADO LIBROS
    #[Route('/libro', name: 'app_libro')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener todos los libros desde la base de datos
        $libros = $entityManager->getRepository(Libro::class)->findAll();

        // Pasar los libros a la plantilla
        return $this->render('libro/index.html.twig', [
            'libros' => $libros,
        ]);
    }

    //RUTA PARA MOSTRAR FORMULARIO INSERCION
    #[Route('/libro/nuevo', name: 'app_libro_nuevo')]
    //esto hace que cuando se accede a http://localhost:8000/libro/nuevo se ejecuta el metodo nuevo()
    //ademas con el name lo que hace es darle un nombre para generar un enlace el cual se llame por el nombre y no por la ruta
    public function nuevo(Request $request, EntityManagerInterface $entityManager): Response{
        //request -> objeto de la solicitud, datos del formulario
        //entityManager -> servicio de Doctrine que permite interactuar con la bbdd
        //response: tipo de retorno de esta funcion que sera enviada al navegador (pagina renderizada o redireccion)
        
        //crea una nueva instancia
        $libro = new Libro();
        //genera un formulario a la clase LibroType
        //el formulario esta vinculado a la instancia $libro -> que cuando sus campos se llenen crearan el objeto libro
        $form = $this->createForm(LibroType::class, $libro);
        //**LibroType::class** define como debe ser el formulario (que campos tiene, validaciones...)


        //procesa los datos del formulario enviados en la solicitud HHTP
        //$request contiene datos formulario y mapea al objeto $libro
        $form->handleRequest($request);

        //si el forulario se ha enviado y es valido (que todas las validaciones sean true)
        if ($form->isSubmitted() && $form->isValid()) {
            //marc el objeto $libro de ser pndiente de guardado a la bbdd

            //le dice a Doctrine que el objeto debe ser guardado en la bbdd cuando se ejecute flush()
            $entityManager->persist($libro);
            $entityManager->flush();  // -> operacion de guardado en la bbdd

            //despues de guardar el libro nuevo rdirige a la lista de todos los libros
            return $this->redirectToRoute('app_libro');
        }

        //si el formulario no es valido o no es valido, se vuelve a mostrar el formulario
        return $this->render('libro/nuevo.html.twig', [
            //render -> genera la respuesta HTML, renderizando la plantilla
            'form' => $form->createView(), 
            //convierte el objeto del formulario en una representación que Twig puede procesar y mostrar en la vista.
        ]);
    }


    //la ruta acepta un parametro {id} que corresponde al id del libro
    #[Route('/libro/{id}/eliminar', name: 'app_libro_eliminar')]
    public function eliminar(int $id, EntityManagerInterface $entityManager): Response{
        // Buscar el libro por su ID
        // Utilizamos el repositorio de la entidad 'Libro' para buscar el libro con el ID que hemos recibido en la URL.
        $libro = $entityManager->getRepository(Libro::class)->find($id);

        //si el libro existe nada, sino existe lanza una excepcion 
        if (!$libro) {
            //excepcion
            throw $this->createNotFoundException('El libro no existe.');
        }

        // Eliminar el libro
        $entityManager->remove($libro);
        // guardar cambios
        $entityManager->flush();

        // Redirigir de nuevo a la lista de libros
        return $this->redirectToRoute('app_libro');
    }

    #[Route('/libro/{id}/editar', name: 'app_libro_editar')]
    public function editar(int $id, Request $request, EntityManagerInterface $entityManager): Response{
        $libro = $entityManager->getRepository(Libro::class)->find($id);

        if (!$libro) {
            throw $this->createNotFoundException('El libro no existe.');
        }

        $form = $this->createForm(LibroType::class, $libro);

        // Procesar los datos del formulario
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Guardar los cambios en la base de datos
            $entityManager->flush();

            // Redirigir al listado de libros
            return $this->redirectToRoute('app_libro');
        }

        // Renderizar(muestra y genera una representacion grafica) la vista con el formulario
        return $this->render('libro/editar.html.twig', [
            'form' => $form->createView(),
        ]);

    }


}
