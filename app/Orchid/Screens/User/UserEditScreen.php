<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Models\Datasheet;
use App\Models\History;
use App\Models\User as Users;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\Impersonation;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;

class UserEditScreen extends Screen
{
    public $user;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Users $user): iterable
    {
        $user->load(['roles','datasheet','history']);
        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->user->exists ? 'Editar paciente' : 'Añadir paciente';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Perfil de usuario y privilegios, incluido su rol asociado';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        if(Auth::user()->inRole('doctor') && $this->user->exists){
            return [
                Link::make('Volver')
                    ->icon('bs.arrow-left')
                    ->route('platform.systems.users'),

                Link::make('Consultas')
                    ->icon('bs.inboxes')
                    ->route('platform.inquiry.user',$this->user->id),

                Link::make('Historial clínico')
                    ->icon('bs.list')
                    ->route('platform.historical.edit',$this->user->history->id),

                Link::make('Ficha técnica')
                    ->icon('bs.person')
                    ->route('platform.datasheet.edit',$this->user->datasheet->id),

                Button::make(__('Save'))
                    ->icon('bs.check-circle')
                    ->method('save'),
            ];
        } elseif(Auth::user()->inRole('doctor') && !$this->user->exists) {
            return [
                Link::make('Volver')
                    ->icon('bs.arrow-left')
                    ->route('platform.systems.users'),

                Button::make(__('Save'))
                    ->icon('bs.check-circle')
                    ->method('save'),
            ];
        } else {
            return [
                Button::make(__('Impersonate user'))
                    ->icon('bg.box-arrow-in-right')
                    ->confirm(__('You can revert to your original state by logging out.'))
                    ->method('loginAs')
                    ->canSee($this->user->exists && \request()->user()->id !== $this->user->id),

                Button::make(__('Remove'))
                    ->icon('bs.trash3')
                    ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                    ->method('remove')
                    ->canSee($this->user->exists),

                Button::make(__('Save'))
                    ->icon('bs.check-circle')
                    ->method('save'),
            ];
        }

    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        if(Auth::user()->inRole('doctor')){

            return [
                Layout::block(UserEditLayout::class)
                    ->title(__('Profile Information'))
                    ->description(__('Update your account\'s profile information and email address.'))
                    ->commands(
                        Button::make(__('Save'))
                            ->type(Color::BASIC)
                            ->icon('bs.check-circle')
                            ->canSee($this->user->exists)
                            ->method('save')
                    ),

                Layout::block(UserPasswordLayout::class)
                    ->title(__('Password'))
                    ->description(__('Ensure your account is using a long, random password to stay secure.'))
                    ->commands(
                        Button::make(__('Save'))
                            ->type(Color::BASIC)
                            ->icon('bs.check-circle')
                            ->canSee($this->user->exists)
                            ->method('save')
                    ),

            ];

        } else {

            return [
                Layout::block(UserEditLayout::class)
                    ->title(__('Profile Information'))
                    ->description(__('Update your account\'s profile information and email address.'))
                    ->commands(
                        Button::make(__('Save'))
                            ->type(Color::BASIC)
                            ->icon('bs.check-circle')
                            ->canSee($this->user->exists)
                            ->method('save')
                    ),

                Layout::block(UserPasswordLayout::class)
                    ->title(__('Password'))
                    ->description(__('Ensure your account is using a long, random password to stay secure.'))
                    ->commands(
                        Button::make(__('Save'))
                            ->type(Color::BASIC)
                            ->icon('bs.check-circle')
                            ->canSee($this->user->exists)
                            ->method('save')
                    ),

                Layout::block(UserRoleLayout::class)
                    ->title(__('Roles'))
                    ->description(__('A Role defines a set of tasks a user assigned the role is allowed to perform.'))
                    ->commands(
                        Button::make(__('Save'))
                            ->type(Color::BASIC)
                            ->icon('bs.check-circle')
                            ->canSee($this->user->exists)
                            ->method('save')
                    ),

                Layout::block(RolePermissionLayout::class)
                    ->title(__('Permissions'))
                    ->description(__('Allow the user to perform some actions that are not provided for by his roles'))
                    ->commands(
                        Button::make(__('Save'))
                            ->type(Color::BASIC)
                            ->icon('bs.check-circle')
                            ->canSee($this->user->exists)
                            ->method('save')
                    ),
            ];
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
        ]);

        $permissions = collect($request->get('permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
            $builder->getModel()->password = Hash::make($request->input('user.password'));
        });

        $user
            ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
            ->fill(['permissions' => $permissions])
            ->save();

        $history = History::where('user_id',$user->id)->first();
        $datasheet = Datasheet::where('user_id',$user->id)->first();
        if(!$history){
            $history = new History();
            $history->user_id = $user->id;
            $history->save();
        }

        if(!$datasheet){
            $datasheet = new Datasheet();
            $datasheet->user_id = $user->id;
            $datasheet->save();
        }

        if(Auth::user()->inRole('doctor') && empty($request->input('user.roles'))){
            $user->replaceRoles([3]);
            $user->doctor_id = Auth::user()->id;
        } else {
            $user->replaceRoles($request->input('user.roles'));
        }

        $user->save();
        Toast::info(__('El paciente ha sido actualizado.'));
        return redirect()->route('platform.systems.users.edit',$user->id);
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(User $user)
    {
        $user->delete();
        Toast::info(__('El paciente fue borrado'));
        return redirect()->route('platform.systems.users');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(User $user)
    {
        Impersonation::loginAs($user);
        Toast::info(__('Ahora estás suplantando a esta usuario.'));
        return redirect()->route(config('platform.index'));
    }
}
