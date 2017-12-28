<?php

namespace Admin\Controller;

use Application\Controller\BaseAdminController as BaseController;
use Admin\Form\CategoryAddForm;
use Blog\Entity\BlogCategory;

class CategoryController extends BaseController
{
    /**
     *
     */
    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT u FROM Blog\Entity\BlogCategory u ORDER BY u.id ASC");
        $rows = $query->getResult();

        return [
            'category' => $rows
        ];
    }

    public function addAction()
    {
        $form = new CategoryAddForm();
        $status = $message = '';
        $em = $this->getEntityManager();

        $request = $this->getRequest();

        if($request->isPost()) {
                $form->setData($request->getPost());
                if($form->isValid()) {

                    $category = new BlogCategory();
                    $category->exchangeArray($form->getData());

                    $em->persist($category);
                    $em->flush();

                    $status = 'success';
                    $message = 'Статья была успешно добавлена';

                } else {
                    $status = 'error';
                    $message = 'Произошла ошибка при добавлении статьи';
                }
        } else {
            return ['form' => $form];
        }

        if ($message) {
            $this->flashMessenger()->setNamespace($status)->addMessage($message);
        }

        return $this->redirect()->toRoute('admin/category');
    }

    public function editAction()
    {
        $status = $message = '';
        $em = $this->getEntityManager();
        $form = new CategoryAddForm();

        $id = (int) $this->params()->fromRoute('id', 0);

        $category = $em->find('Blog\Entity\BlogCategory', $id);

        if(!$category) {
            $message = 'Категория не найдена';
            $status = 'error';
            $this->flashMessenger()->setNamespace($status)->addMessage($message);
            return $this->redirect()->toRoute('admin/category');
        }

        $form->bind($category);

        $request = $this->getRequest();

        if($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if($form->isValid()) {
                $em->persist($category);
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

        return $this->redirect()->toRoute('admin/category');
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $em = $this->getEntityManager();

        $status = 'success';
        $message = 'Успешно удалили категорию';

        try {
            $repository = $em->getRepository('Blog\Entity\BlogCategory');
            $category = $repository->find($id);
            $em->remove($category);
            $em->flush();
        } catch (\Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
        }

        $this->flashMessenger()->setNamespace($status)->addMessage($message);

        return $this->redirect()->toRoute('admin/category');
    }
}