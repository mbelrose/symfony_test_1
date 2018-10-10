<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Entity\CagetoryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController2 extends AbstractController
{

    
    private static function rWord (int $length) : string {
        $ASCII_A = 65;
        $ASCII_Z = 90;

        $randomWord = '';
        for ($i=0; $i < $length; $i++) {
            $randomWord = $randomWord .chr(rand($ASCII_A, $ASCII_Z));
        }
        return $randomWord;
    }
    
    /**
     * @Route("/create", name="product_create", methods="GET")
     */
    public function createAction() {

        $category = new Category;
        $category->setName('cat_'.self::rWord(5));
        $product = new Product();
        $product->setName('prod_'.self::rWord(5));
        $product->setPrice(rand(0, 20));
        $product->setDescription('desc_'.self::rWord(5));
        $product->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($product);
        $em->flush();

        return new Response(
                  'Saved new product with id '. $product->getID()
                . '<br>Save new category with id '. $product->getCategory()->getID()
        );
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
    
}
