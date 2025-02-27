<?php

namespace App\Controllers;

use App\Models\Area;
use App\Models\AreaApproval;
use App\Models\AreaUserReinforce;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class ReinforceAreasController extends Controller
{
    public function index(Request $request): void
    {
        $paginator = Area::paginate(page: $request->getParam('page', 1), route: 'reinforce.areas.paginate');
        $areas = $paginator->registers();

        $title = 'Todas as Areas';

        $this->render('reinforce_areas/index', compact('paginator', 'areas', 'title'));
    }

    public function supported(): void
    {
        $areas = $this->current_user->reinforced_areas;
        $title = 'Area Suportadas';

        $this->render('reinforce_areas/supported', compact('areas', 'title'));
    }

    public function support(Request $request): void
    {
        $area_id = $request->getParam('id');

        $areaReinforce = new AreaUserReinforce(
            ['user_id' => $this->current_user->id, 'area_id' => $area_id]
        );

        if ($areaReinforce->save()) {
            FlashMessage::success('Area suportada com sucesso.');
        } else {
            $message = $areaReinforce->errors('user_id');
            FlashMessage::danger('Erro ao suportar area: ' . $message);
        }

        $this->redirectBack();
    }

    public function stoppedSupporting(Request $request): void
    {
        $area_id = $request->getParam('id');

        $areaReinforce = AreaUserReinforce::findBy(
            ['user_id' => $this->current_user->id, 'area_id' => $area_id]
        );

        if ($areaReinforce == null) {
            FlashMessage::danger('Erro ao parar de suportar area: você não está suportando esta area.');
        } else {
            $areaReinforce->destroy();
            FlashMessage::success('Você parou de suportar o area.');
        }

        $this->redirectBack();
    }

    public function updateStatus(Request $request): void
    {
        if (!$this->current_user->isAdmin()) {
            FlashMessage::danger('Você não tem permissão para realizar essa ação.');
            $this->redirectBack();
            return;
        }

        $area_id = $request->getParam('id');
        $status = $request->getParam('status');

        $area = Area::findById($area_id);
        if (!$area) {
            FlashMessage::danger('Área não encontrada.');
            $this->redirectBack();
            return;
        }
        
        $area->status = $status;

        $approval = new AreaApproval([
            'user_id' => $this->current_user->id,
            'area_id' => $area_id,
            'status_id' => $status
        ]);

        $approval->created_at = date('Y-m-d H:i:s');

        if ($area->save() && $approval->save()) {
            FlashMessage::success('Status atualizado com sucesso.');
        } else {
            FlashMessage::danger('Erro ao atualizar o status.');
        }

        $this->redirectBack();
    }
}
