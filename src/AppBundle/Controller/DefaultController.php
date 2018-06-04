<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Model\Combination;

class DefaultController extends Controller
{
    

	
	/**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
		$result = "";
		
		$form = $this->createFormBuilder()
			->add('fieldsCount')
			->add('chipCount')
			->add('check', SubmitType::class, array('label' => 'Посчитать на страницу'))
			->add('save', SubmitType::class, array('label' => 'Посчитать в файл'))
			->getForm()
		;
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) 
		{

			$fieldsCount = $form->get('fieldsCount')->getData();
			$chipCount = $form->get('chipCount')->getData();
			
			$comb =new Combination($chipCount,$fieldsCount);
			
			if($comb->getCount() < 10 )
			{
				$result .= "Менее 10 вариантов";
			}
			else
			{
				$result .= $comb->getCount().PHP_EOL;
				$result .= $comb->getResult();
			}			
			
			// если в файл
			if ($form->get('save')->isClicked()) {
				$response = new Response($result);
				$disposition = $response->headers->makeDisposition(
					ResponseHeaderBag::DISPOSITION_ATTACHMENT,
					'combinations.txt'
				);
				$response->headers->set('Content-Disposition', $disposition);	
				return $response;
			}			
		}
		
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
			'result' => str_replace(PHP_EOL,'<br>', $result),
        ]);
    }
}
