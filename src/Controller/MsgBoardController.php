<?php
namespace App\Controller;

use App\Entity\MsgBoard;
use App\Entity\Reply;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MsgBoardController extends AbstractController
{
    /**
     * @Route("/MsgBoard/index")
     */
    public function index()
    {
        $page = isset($_GET['p']) ? $_GET['p'] : 1;
        $page = ($page === 0) ? 1 : $page;
        $num = 5;
        $offset = ($page - 1) * $num;
        $dql = 'SELECT m FROM App\Entity\MsgBoard m ORDER BY m.id DESC';
        $query = $this->getDoctrine()
            ->getManager()
            ->createQuery($dql)
            ->setFirstResult($offset)
            ->setMaxResults($num);

        $comments = new Paginator($query, $fetchJoinCollection = true);

        $totalComments = count($comments);
        $totalPage = ceil($totalComments / $num);
        $page = ($page === $totalPage) ? ($totalPage - 1) : $page;

        return $this->render('msgBoard/msg.html.twig', [
            'totalComments' => $totalComments,
            'totalPage' => $totalPage,
            'comments' => $comments,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/MsgBoard/getMsgReply/{pmId}")
     */
    public function getMsgReply($pmId)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT r FROM App\Entity\Reply r WHERE r.tag = ' . $pmId);
        $users = $query->getArrayResult();
        return new Response(
            json_encode($users)
        );
    }

    /**
     * @Route("/MsgBoard/sendMsgReply/{pmId}")
     */
    public function sendMsgReply($pmId, EntityManagerInterface $em)
    {
        $names = $_POST['names'];
        $contents = $_POST['contents'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $times = date('Y-m-d H:i:s');
        $replyInsert = new Reply;
        $replyInsert->setContents($contents);
        $replyInsert->setOwner($names);
        $replyInsert->setTimes($times);
        $replyInsert->setUserIp($ip);
        $replyInsert->setTag($pmId);
        $em->persist($replyInsert);
        $em->flush();
        return new RedirectResponse('http://localhost:8000/MsgBoard/index');
    }

    /**
     * @Route("/MsgBoard/newMsgReply")
     */
    public function newMsgReply(EntityManagerInterface $em)
    {
        $names = $_POST['names'];
        $contents = $_POST['contents'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $times = date('Y-m-d H:i:s');
        $msgInsert = new MsgBoard;
        $msgInsert->setContents($contents);
        $msgInsert->setOwner($names);
        $msgInsert->setTimes($times);
        $msgInsert->setUserIp($ip);
        $em->persist($msgInsert);
        $em->flush();
        return new RedirectResponse('http://localhost:8000/MsgBoard/index');
    }

    /**
     * @Route("/MsgBoard/updateMsgReply/{id}")
     */
    public function updateMsgReply($id, EntityManagerInterface $em)
    {
        $contents = $_POST['contents'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $msgBoard = $em->find('App\Entity\MsgBoard', $id);
        $msgBoard->setContents($contents);
        $msgBoard->setUserIp($ip);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return new RedirectResponse('http://localhost:8000/MsgBoard/index');
    }

    /**
     * @Route("/MsgBoard/delMsgReply")
     */
    public function delMsgReply(EntityManagerInterface $em)
    {
        $id = $_POST['listId'];
        $msgBoard = $em->getRepository('App\Entity\MsgBoard')->find($id);
        if ($msgBoard) {
            $em->remove($msgBoard);
        }

        $replyBoard = $em->getRepository('App\Entity\Reply')->findBy(['tag' => $id]);

        if ($replyBoard) {
            foreach ($replyBoard as $value) {
                $em->remove($value);
            }
        }

        $em->flush();
        $em->clear();
        return new RedirectResponse('http://localhost:8000/MsgBoard/index');
    }
}
