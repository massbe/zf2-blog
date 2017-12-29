<?php


namespace Admin\Controller;

use Application\Controller\BaseAdminController as BaseController;
use Blog\Entity\BlogArticle;
use Doctrine\ORM\Mapping\Entity;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Admin\Form\ArticleAddForm;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


class ArticleController extends BaseController
{
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query
            ->select('a')
            ->from('\Blog\Entity\BlogArticle', 'a')
            ->orderBy('a.id', 'DESC');

        $adapter = new DoctrineAdapter(new ORMPaginator($query));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));

        return ['articles' => $paginator];
    }

    public function addAction()
    {
        $em = $this->getEntityManager();
        $form = new ArticleAddForm($em);

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $message = $status = '';

            $data = $request->getPost();

            $article = new BlogArticle();

            $form->setHydrator(new DoctrineHydrator($em, '\BlogArticle'));
            $form->bind($article);
            $form->setData($data);

            if ($form->isValid()) {
                $em->persist($article);
                $em->flush();

                $status = 'success';
                $message = 'Статья добавлена';
            } else {
                $status = 'error';
                $message = 'Ошибка параметров';
                foreach ($form->getInputFilter()->getInvalidInput() as $errors) {
                    foreach ($errors->getMessages() as $error) {
                        $message .= ' ' . $error;
                    }
                }
            }
        } else {
            return array('form' => $form);
        }

        if ($message) {
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }

        return $this->redirect()->toRoute('admin/article');
    }

    public function editAction()
    {
        $status = $message = '';
        $em = $this->getEntityManager();
        $form = new ArticleAddForm($em);

        $id = (int) $this->params()->fromRoute('id', 0);

        $article = $em->find('Blog\Entity\BlogArticle', $id);

        if(!$article) {
            $message = 'Категория не найдена';
            $status = 'error';
            $this->flashMessenger()->setNamespace($status)->addMessage($message);
            return $this->redirect()->toRoute('admin/article');
        }

        $form->setHydrator(new DoctrineHydrator($em, 'BlogArticle'));
        $form->bind($article);

        $request = $this->getRequest();

        if($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if($form->isValid()) {
                $em->persist($article);
                $em->flush();

                $status = 'success';
                $message = 'Категория успешно обновлена';
            } else {
                $status = 'error';
                $message = 'Произошел сбой при обновлении';

                foreach ($form->getInputFilter()->getInvalidInput() as $errors) {
                    foreach ($errors as $error) {
                        $message .= ' '. $error;
                    }
                }
            }
        } else {
            return ['form' => $form, 'id' => $id];
        }

        if($message) {
            $this->flashMessenger()->setNamespace($status)->addMessage($message);
        }

        return $this->redirect()->toRoute('admin/article');
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $em = $this->getEntityManager();

        $status = 'success';
        $message = 'Успешно удалили категорию';

        try {
            $repository = $em->getRepository('Blog\Entity\BlogArticle');
            $article = $repository->find($id);
            $em->remove($article);
            $em->flush();
        } catch (\Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
        }

        $this->flashMessenger()->setNamespace($status)->addMessage($message);

        return $this->redirect()->toRoute('admin/article');
    }
}