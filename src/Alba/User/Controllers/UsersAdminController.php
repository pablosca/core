<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Input;

/**
 * Controller for accessing UsersResource from a backend web interface
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\UsersController
 */
class UsersAdminController extends \AlbaUsersController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'alba::core.default';

    /**
     * Show confirmation modal
     * 
     * @param integer $id
     * @param string $view
     * @return void
     */
    protected function confirm($id, $view)
    {
        $object = $this->getApi()->show($id);
        $this->modal($view . '_confirm', ['user' => $object]);
    }

    /**
     * Display a listing of the trashed resource.
     *
     * @return void
     */
    public function trash()
    {
        Input::merge(['trashed' => 'only']);
        $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param boolean $withTrashed
     * @return void
     */
    public function show($id, $withTrashed = true)
    {
        // Make sure to include trashed
        parent::show($id, $withTrashed);
    }

    /**
     * Show confirmation modal to reset activation
     * 
     * @param integer $id
     * @return void
     */
    public function resetActivationConfirm($id)
    {
        $this->confirm($id, 'reset_activation');
    }

    /**
     * Show confirmation modal to reset password
     * 
     * @param integer $id
     * @return void
     */
    public function resetPasswordConfirm($id)
    {
        $this->confirm($id, 'reset_password');
    }

    /**
     * Send user password reset URL and redirec to user profile with success message
     * or redirect to forgot password page with errors
     *
     * @param integer $id of User
     * @return Redirect
     */
    public function resetPassword($id = null)
    {
        // Prefer to use $id over email for admins
        if ($id) // @todo restrict to admin privileges
        {
            $object = $this->getApi()->show($id);
        }
        
        // Send activation email to user
        $email = isset($object->email) ? $object->email : Input::get('email');
        $object = $this->getResource()->resetPassword($email);
        return $this->redirect('reset_password', ['id' => $object->id])
            ->with('message', $this->language('success.reset_password', ['email' => $object->email]));
    }

    /**
     * Show confirmation modal to destroy
     * 
     * @param integer $id
     * @return void
     */
    public function destroyConfirm($id)
    {
        $object = $this->getResource()->show($id, true);
        $this->modal('destroy_confirm', ['user' => $object]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function destroy($id)
    {
        // @todo what about security here?

        $this->getApi()->destroy($id);

        return $this->redirect('destroy')
            ->with('message', $this->language('success.destroy'));
    }

    /**
     * Show confirmation modal to restore
     * 
     * @param integer $id
     * @return void
     */
    public function restoreConfirm($id)
    {
        $object = $this->getResource()->show($id, true);
        $this->modal('restore_confirm', ['user' => $object]);
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function restore($id)
    {
        // @todo what about security here?

        $this->getApi()->restore($id);

        return $this->redirectBack('restore')
            ->with('message', $this->language('success.restore'));
    }

    /**
     * Show confirmation modal to activate user
     * 
     * @param integer $id
     * @return void
     */
    public function activateConfirm($id)
    {
        $this->confirm($id, 'activate');
    }

    /**
     * Activate the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function activate($id)
    {
        $object = $this->getResource()->activate($id);

        // Redirect to user profile
        return $this->redirectBack('activate.user', ['id' => $object->id])
            ->with('message', $this->language('success.activate'));
    }

    /**
     * Show confirmation modal to deactivate user
     * 
     * @param integer $id
     * @return void
     */
    public function deactivateConfirm($id)
    {
        $this->confirm($id, 'deactivate');
    }

    /**
     * Deactivates the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function deactivate($id)
    {
        // @todo what about security here?

        $object = $this->getResource()->deactivate($id);
        
        return $this->redirectBack('deactivate', ['id' => $id])
            ->with('message', $this->language('success.deactivate'));
    }

    /**
     * Show confirmation modal to block user
     * 
     * @param integer $id
     * @return void
     */
    public function blockConfirm($id)
    {
        $this->confirm($id, 'block');
    }

    /**
     * Blocks the specified user
     * 
     * @param integer $id
     * @return Redirect
     */
    public function block($id)
    {
        // @todo what about security here?

        $object = $this->getResource()->block($id);

        return $this->redirectBack('block', ['id' => $id])
            ->with('message', $this->language('success.block'));
    }

    /**
     * Unblocks the specified user 
     * 
     * @param integer $id
     * @return Redirect
     */
    public function unblock($id)
    {
        // @todo what about security here?

        $object = $this->getResource()->unblock($id);
        
        return $this->redirectBack('unblock', ['id' => $id])
            ->with('message', $this->language('success.unblock'));
    }

    /**
     * Show confirmation modal to unblock user
     * 
     * @param integer $id
     * @return void
     */
    public function unblockConfirm($id)
    {
        $this->confirm($id, 'unblock');
    }

    /**
     * Show the form for updating the roles attached to the specified resource in storage.
     *
     * @param int $id of object to update
     * @return void
     */
    public function editRoles($id)
    {
        // @todo what about security here?

        // Get user object
        $object = $this->getApi()->show($id);
        
        // Get options
        $rolesOptions = $this->getResource()->getModel('role')->listAlphabetically();
        $roles = isset($object) ? $object->roles->lists('id') : [];

        // Parse view data
        $data = compact('rolesOptions', 'roles');
        if(!is_null($object))
        {
            $data['user'] = $object;
        }

        $this->modal('edit_roles')
            ->with($data);
    }

    /**
     * Update the roles attached to the specified resource in storage.
     *
     * @param int $id of object to update
     * @return Redirect
     */
    public function assignRoles($id)
    {
        // @todo what about security here?

        $object = $this->getApi()->assignRoles($id);

        return $this->redirect('assign_roles', ['id' => $id])
            ->with('message', $this->language('success.assign_roles'));
    }
}