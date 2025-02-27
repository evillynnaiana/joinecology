<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Enums\AreaStatus;
use App\Models\Area;
use App\Models\AreaImage;
use App\Services\AreaImageManager;

class AreasController extends Controller
{
    public function index(Request $request): void
    {
        if ($this->current_user->isAdmin()) {
            $this->redirectTo(route('reinforce.areas'));
            return;
        }

        $paginator = $this->current_user->areas()->paginate(page: $request->getParam('page', 1));
        $areas = $paginator->registers();

        $title = 'Minhas Areas';

        if ($request->acceptJson()) {
            $this->renderJson('areas/index', compact('paginator', 'areas', 'title'));
        } else {
            $this->render('areas/index', compact('paginator', 'areas', 'title'));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $area = Area::findById($params['id']);

        if (!$area) {
            FlashMessage::danger('Área não encontrada.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        if (!$this->current_user->isAdmin() && $area->user_id !== $this->current_user->id) {
            FlashMessage::danger('Você não tem permissão para ver esta área.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $timeline = \App\Models\AreaImage::allByArea($area->id);

        $title = "Visualização da Area #{$area->id}";
        $this->render('areas/show', compact('area', 'title', 'timeline'));
    }

    public function uploadImage(Request $request): void
    {
        if ($this->current_user->isAdmin()) {
            FlashMessage::danger('Administradores não podem enviar fotos de áreas.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $params = $request->getParams();
        /** @var Area|null $area */
        $area = Area::findById($params['id']);

        if (!$area) {
            FlashMessage::danger('Área não encontrada.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        if (in_array($area->status, [AreaStatus::ACCEPTED, AreaStatus::REJECTED])) {
            FlashMessage::danger('Você não pode adicionar imagens a uma área que foi aceita ou rejeitada.');
            $this->redirectTo(route('areas.show', ['id' => $area->id]));
            return;
        }

        $uploaded = false;
        if (isset($_FILES['area_image'])) {
            $manager = new AreaImageManager($area);
            $uploaded = $manager->upload($_FILES['area_image']);
        }

        if ($uploaded) {
            FlashMessage::success('Imagem adicionada ao histórico com sucesso!');
        } else {
            FlashMessage::danger('Falha no upload da imagem.');
        }
        $this->redirectTo(route('areas.show', ['id' => $area->id]));
    }

    public function new(): void
    {
        if ($this->current_user->isAdmin()) {
            FlashMessage::danger('Administradores não podem cadastrar áreas.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $area = $this->current_user->areas()->new();

        $title = 'Nova Area';
        $this->render('areas/new', compact('area', 'title'));
    }

    public function create(Request $request): void
    {
        if ($this->current_user->isAdmin()) {
            FlashMessage::danger('Administradores não podem cadastrar áreas.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $params = $request->getParams();
        /** @var Area $area */
        $area = $this->current_user->areas()->new($params['area']);
        $area->status = AreaStatus::PENDING;

        if ($area->save()) {
            if (isset($_FILES['area_image'])) {
                $manager = new AreaImageManager($area);
                $manager->upload($_FILES['area_image']);
            }
            FlashMessage::success('Area registrada com sucesso!');
            $this->redirectTo(route('areas.index'));
        } else {
            FlashMessage::danger('Existem dados incorretos! Por verifique!');
            $title = 'Nova Area';
            $this->render('areas/new', compact('area', 'title'));
        }
    }

    public function edit(Request $request): void
    {
        if ($this->current_user->isAdmin()) {
            FlashMessage::danger('Administradores não podem editar áreas.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $params = $request->getParams();
        $area = $this->current_user->areas()->findById($params['id']);

        if ($area->status !== AreaStatus::PENDING) {
            FlashMessage::danger('Você só pode editar uma área que está pendente.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $title = "Editar Area #{$area->id}";
        $this->render('areas/edit', compact('area', 'title'));
    }

    public function update(Request $request): void
    {
        if ($this->current_user->isAdmin()) {
            FlashMessage::danger('Administradores não podem atualizar áreas.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $id = $request->getParam('id');
        $params = $request->getParam('area');

        $area = $this->current_user->areas()->findById($id);

        if ($area->status !== AreaStatus::PENDING) {
            FlashMessage::danger('Você só pode atualizar uma área que está pendente.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $area->title = $params['title'];
        $area->street = $params['street'];
        $area->city = $params['city'];
        $area->state = $params['state'];
        $area->zipcode = $params['zipcode'];
        $area->number = $params['number'];
        $area->status = $params['status'] ?? AreaStatus::PENDING;

        if ($area->save()) {
            FlashMessage::success('Area atualizada com sucesso!');
            $this->redirectTo(route('areas.index'));
        } else {
            FlashMessage::danger('Existem dados incorretos! Por verifique!');
            $title = "Editar Area #{$area->id}";
            $this->render('areas/edit', compact('area', 'title'));
        }
    }

    public function destroy(Request $request): void
    {
        if ($this->current_user->isAdmin()) {
            FlashMessage::danger('Administradores não podem remover áreas.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $params = $request->getParams();

        $area = $this->current_user->areas()->findById($params['id']);

        if ($area->status !== AreaStatus::PENDING) {
            FlashMessage::danger('Você só pode remover uma área que está pendente.');
            $this->redirectTo(route('areas.index'));
            return;
        }

        $area->destroy();

        FlashMessage::success('Area removida com sucesso!');
        $this->redirectTo(route('areas.index'));
    }
}
