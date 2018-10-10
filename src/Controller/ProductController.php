<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods="GET")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', ['products' => $productRepository->findAll()]);
    }

    /**
     * @Route("/new", name="product_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/create", name="product_create", methods="GET")
     */
    public function createAction() {

        $category = new Category;
        $category->setName('Computer Peripherals.');
        $product = new Product();
        $product->setName('plug');
        $product->setPrice(1.96);
        $product->setDescription('ok');
        $product->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($product);
        $em->flush();

        return new Response('Saved new product with id '. $product->getID());
    }

    /**
     * @Route("/show_name/{productID}", name="show_name")
     */
    public function showName($productID) {
        $product = $this->getDoctrine()
                ->getRepository('App:Product')
                ->find($productID);
        
        if (!$product) {
            throw $this->createNotFoundException(
                    'no product found for '.$productID
                );
        } else {
            return $this->render('product/showname.html.twig', ["product" => $product]);
        }
        
    }
    
    /**
     * @Route("/inc_name", name="inc_name")
     */
    public function incName(Request $request) {
        $productID = $request->query->get("id");
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('App:Product')->find($productID);
        
        if (!$product) {
            throw $this->createNotFoundException(
                    'no product found for '. $productID
                    );
        }
        
        $product->setName($product->getName() . "+");
        
        $em->flush();
        
        return $this->redirectToRoute("show_name", ["productID" => $productID]);
        
    }

    /**
     * @Route("/list_by_name", name="list_by_name")
     */
    public function listByName() {
        
        $em = $this->getDoctrine()->getManager();

        return $this->render('product/list_by_name.html.twig', ['product_list' => 
            $em->getRepository('App:Product')->findAllOrderdByName()
        ]);
        
    }
    
    /**
     * @Route("/{id}", name="product_show", methods="GET")
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
