<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Validations;
use Lib\FlashMessage;

class ProfileController extends Controller
{
    public function show(): void
    {
        $title = 'Opções da Conta';
        $this->render('profile/show', compact('title'));
    }

    public function updateAvatar(): void
    {
        $image = $_FILES['user_avatar'];

        $this->current_user->avatar()->update($image);
        $this->redirectTo(route('profile.show'));
    }

    public function update(): void
    {
        $name = $_POST['user_name'];
        $email = $_POST['user_email'];
        $password = $_POST['user_password'];
        $password_confirmation = $_POST['user_password_confirmation'];

        $this->current_user->name = $name;
        $this->current_user->email = $email;

        $valid = Validations::notEmpty('name', $this->current_user) &&
                 Validations::notEmpty('email', $this->current_user) &&
                 Validations::uniqueness('email', $this->current_user);

        if (!empty($password)) {
            $this->current_user->password = $password;
            $this->current_user->password_confirmation = $password_confirmation;
            $valid = $valid && Validations::passwordConfirmation($this->current_user);

            if (!$valid) {
                FlashMessage::danger('Erro ao atualizar usuário. As senhas não coincidem.');
            }
        }

        if ($valid) {
            if (!empty($password)) {
                $this->current_user->encrypted_password = password_hash($password, PASSWORD_DEFAULT);
            }
            $this->current_user->save();
            FlashMessage::success('Usuário atualizado com sucesso!');
        } else if (empty($password)) {
            FlashMessage::danger('Erro ao atualizar usuário. Verifique os dados e tente novamente.');
        }

        $this->redirectTo(route('profile.show'));
    }
}
