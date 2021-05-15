<?php


namespace App\Controller;


use App\Entity\Trajet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController
{
    /**
     * @Route("/home/recherche",name="recherche")
     */
    public function rechercher(Request $request)
    {
        $page=$request->query->get('page') ?? 1;
        $repositery=$this->getDoctrine()->getRepository(Trajet::class);
        $city = file_get_contents(__Dir__.'/../../public/Json/Cities.json');
        $cities=json_decode($city,true);
        $total=$repositery->count(array());
        $nbrPages = ($total % 20) ? (($total - $total % 20) / 20) + 1 : $total / 20;
        $trajet = $repositery->findBy([], ['Date' => 'ASC'], 20, ($page - 1) * 20);
        return $this->render("home/Recherche.html.twig",
            ['cities' => $cities,
                'trajets'=>$trajet,
                'pages'=>$nbrPages,
                'currentpage'=>$page,
            ]);
    }

    /**
     * @param $depart
     * @param $arrivee
     * @param $date
     * @Route("/home/filter",name="filter")
     */
    public function filter(Request $request){
        $depart=$request->query->get('depart');
        $arrivee=$request->query->get('arrivee');
        $date=$request->query->get('date');
        $page=$request->query->get('page') ?? 1;
        $repositery=$this->getDoctrine()->getRepository(Trajet::class);
        $total=$repositery->count(array());
        $city = file_get_contents(__Dir__.'/../../public/Json/Cities.json');
        $cities=json_decode($city,true);
        if (!$depart && !$arrivee && !$date)
            $trajet = $repositery->findBy([], ['Date' => 'ASC'], 20, ($page - 1) * 20);
        else {
            if ($depart)
                $trajet = $repositery->findBy(['Depart' => $depart], ['Date' => 'ASC'], 20, ($page - 1) * 20);
            if ($arrivee)
                $trajet = $repositery->findBy(['Arrivee' => $arrivee], ['Date' => 'ASC'], 20, ($page - 1) * 20);
            if ($date) {
                $datetime = new \DateTime($date);
                $trajet = $repositery->findBy(['Date' => $datetime], ['Date' => 'ASC'], 20, ($page - 1) * 20);
            }
            $total = count($trajet);
        }
        $nbrPages = ($total % 20) ? (($total - $total % 20) / 20) + 1 : $total / 20;
        return $this->render("home/Recherche.html.twig",
            ['cities' => $cities,
                'trajets'=>$trajet,
                'pages'=>$nbrPages,
                'currentpage'=>$page,
            ]);
    }
}