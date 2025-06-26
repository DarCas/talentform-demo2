<?php

namespace App\Http\Controllers;

use App\Enums\AlertType;
use App\Mail\RecuperaPasswordMail;
use App\Models\Form;
use App\Models\User;
use App\View\AlertView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades;
use Illuminate\Support\Str;

class BackController extends Controller
{
    /**
     * Controller per gestione pagina index di backend
     *
     * @param Request $req
     * @return Response
     */
    function index(Request $req): Response
    {
        if ($req->get('delete', false)) {
            $alertTemplate = new AlertView('Messaggio cancellato con successo!');
        } else if ($req->get('edit', false)) {
            $alertTemplate = new AlertView('Messaggio modificato con successo!');
        } else if (Facades\Session::get('no-login', false)) {
            $alertTemplate = new AlertView(
                message: 'Non sei autorizzato ad accedere!',
                type: AlertType::Danger,
            );

            Facades\Session::forget('no-login');
        } else if (Facades\Session::get('recupera-password', false)) {
            $alertTemplate = new AlertView(
                message: 'Abbiamo inviato la nuova password all\'indirizzo e-mail indicato.',
                type: AlertType::Success,
            );

            Facades\Session::forget('recupera-password');
        }

        if ($this->imLogged()) {
            $content = $this->renderDatagrid($req);
        } else {
            $content = $this->renderLogin();
        }

        return $this->renderContent(
            content: $content,
            alertTemplate: $alertTemplate ?? null,
            refresh: isset($alertTemplate) ? "5; url=/backend/" : null,
        );
    }

    /**
     * Controller per la gestione della pagina edit di backend
     *
     * @param int $id
     * @return Response
     */
    function edit(int $id): Response
    {
        /**
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */
        $builder = Form::where('id', $id);
        /**
         * @var Form | null $item
         */
        $item = $builder->first();

        $content = view('back.guestbook.edit', [
            'item' => $item,
            'data' => Facades\Session::get('data'),
        ])->render();

        Facades\Session::forget('data');

        return $this->renderContent($content, 'Edit Guestbook');
    }

    /**
     * Controller per l'elaborazione dei dati di modifica ricevuti
     * dal form di edit di backend.
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    function editPost(Request $request, int $id): mixed
    {
        /**
         * Faccio la validazione.
         * Vedi: ~/app/Http/Controllers/FrontController.php@sendmail
         */
        $validator = Facades\Validator::make($request->post(), [
            'nome' => 'required|min:3',
            'cognome' => 'required|min:3',
            'email' => 'required|email:rfc',
            'messaggio' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            /**
             * Salvo i campi già compilati del form.
             */
            Facades\Session::put('data', $validator->getData());

            $errors = $validator->errors();

            /**
             * Salvo con un algoritmo standard di riduzione di una matrice in array key-value,
             * gli errori del form.
             */
            Facades\Session::put('errors', array_reduce($errors->keys(), function ($carry, $key) use ($errors) {
                $carry[$key] = $errors->get($key)[0];

                return $carry;
            }, []));

            /**
             * Reindirizzo
             */
            return redirect("/backend/edit/{$id}");
        }

        /**
         * Recupero il record
         * Vedi: BackendController@edit
         *
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */
        $item = Form::where('id', $id)
            ->first();

        $item->nome = $validator->getValue('nome');
        $item->cognome = $validator->getValue('cognome');
        $item->email = $validator->getValue('email');
        $item->messaggio = $validator->getValue('messaggio');
        $item->save();

        return redirect('/backend/?edit=true');
    }

    /**
     * Controller per la gestione della pagina delete di backend
     *
     * @param int $id
     * @return Response
     */
    function delete(int $id): Response
    {
        /**
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */
        $builder = Form::where('id', $id);
        /**
         * @var Form | null $item
         */
        $item = $builder->first();

        $content = view('back.guestbook.delete', [
            'item' => $item,
        ])->render();

        return $this->renderContent($content, 'Delete Guestbook');
    }

    /**
     * Controller per l'elaborazione dei dati di cancellazione
     * ricevuti dal form di delete di backend.
     *
     * @param int $id
     * @return mixed
     */
    function deletePost(int $id): mixed
    {
        /**
         * Recupero il record
         * Vedi: BackendController@edit
         *
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */
        Form::where('id', $id)
            ->first()
            ->delete();

        return redirect('/backend/?delete=true');
    }

    /**
     * Controller per l'elaborazione dei dati per accesso al backend.
     *
     * @param Request $req
     * @return mixed
     */
    function login(Request $req): mixed
    {
        $validator = Facades\Validator::make($req->post(), [
            'passwd' => 'required|max:255',
            'usernm' => 'required|max:255',
        ]);

        if (!$validator->fails()) {
            /**
             * @var \Illuminate\Database\Eloquent\Builder $builder
             */
            $builder = User::where('usernm', $validator->getValue('usernm'));
            $builder->where('passwd', sha1($validator->getValue('passwd')));

            /**
             * @var User|null $user
             */
            $user = $builder->first();

            /**
             * Versione fluente del codice precedente
             */
//            $user = User::where('usernm', $validator->getValue('usernm'))
//                ->where('passwd', sha1($validator->getValue('passwd')))
//                ->first();

            if (!is_null($user)) {
                Facades\Cookie::queue(
                    'logged',
                    "{$user->id}:" . sha1(http_build_query($user->toArray())),
                    0
                );
            } else {
                Facades\Session::put('no-login', true);
            }
        } else {
            $errors = $validator->errors();

            /**
             * Salvo con un algoritmo standard di riduzione di una matrice in array key-value,
             * gli errori del form.
             */
            Facades\Session::put('errors', array_reduce($errors->keys(), function ($carry, $key) use ($errors) {
                $carry[$key] = $errors->get($key)[0];

                return $carry;
            }, []));
        }

        return redirect('/backend/');
    }

    /**
     * Controller per funzione di logout.
     *
     * @return mixed
     */
    function logout(): mixed
    {
        Facades\Cookie::queue('logged', null, -1);

        return redirect('/backend/');
    }

    /**
     * Controller per la gestione della pagina di recupera password di
     * backend.
     *
     * @return mixed
     */
    function recuperaPassword(): mixed
    {
        if ($this->imLogged()) {
            return redirect('/backend/');
        } else {
            return $this->renderContent(
                content: view('back.recupera-password')->render(),
                alertTemplate: $alertTemplate ?? null,
                refresh: isset($alertTemplate) ? "5; url=/backend/" : null,
            );
        }
    }

    /**
     * Controller per l'elaborazione dei dati ricevuti dalla pagina di
     * recupera password di backend.
     *
     * @param Request $req
     * @return mixed
     */
    function recuperaPasswordPost(Request $req): mixed
    {
        $validator = Facades\Validator::make($req->post(), [
            'usernm' => 'required|max:255',
        ]);

        if (!$validator->fails()) {
            /**
             * @var \Illuminate\Database\Eloquent\Builder $builder
             */
            $builder = User::where('usernm', $validator->getValue('usernm'));

            /**
             * @var User|null $user
             */
            $user = $builder->first();

            if (!is_null($user)) {
                /**
                 * Genero una password di 10 caratteri alfanumerica e con simboli,
                 * utilizzando una funzione built-in di Laravel.
                 */
                $passwd = Str::password(10);

                /**
                 * Memorizzo la password nella tabella del database, dopo averla
                 * crittografata in SHA1.
                 */
                $user->passwd = sha1($passwd);
                $user->save();

                /**
                 * Invio e-mail con Facades.
                 */
                $mail = Facades\Mail::to(new Address(
                    address: $user->usernm,
                    name: $user->fullname,
                ));
                $mail->send(
                    new RecuperaPasswordMail([
                        'usernm' => $user->usernm,
                        'passwd' => $passwd,
                    ])
                );

                Facades\Session::put('recupera-password', true);
            }

            return redirect('/backend/');
        } else {
            $errors = $validator->errors();

            /**
             * Salvo con un algoritmo standard di riduzione di una matrice in array key-value,
             * gli errori del form.
             */
            Facades\Session::put('errors', array_reduce($errors->keys(), function ($carry, $key) use ($errors) {
                $carry[$key] = $errors->get($key)[0];

                return $carry;
            }, []));

            return redirect('/backend/recupera-password');
        }
    }

    /**
     * Elaboro il datagrid dei messaggi
     *
     * @param Request $request
     * @return string
     */
    private function renderDatagrid(Request $request): string
    {
        /**
         * Colonna per la quale ordinare i risultati della tabella
         */
        $column = $request->query('sort', 'id');

        /**
         * Ordinamento ascendente o discendente
         */
        $desc = $request->query('desc', false);

        /**
         * Elementi per pagina
         */
        $perPage = $request->query('perPage', 10);

        /**
         * Recupero tutti i dati dalla tabella del database "form" ordinati
         * per "id".
         *
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */

        if ($column === 'fullname') {
            $builder = Form::orderBy('cognome', $desc ? 'desc' : 'asc');
            $builder->orderBy('nome', $desc ? 'desc' : 'asc');
        } else {
            $builder = Form::orderBy($column, $desc ? 'desc' : 'asc');
        }

        if ($perPage == -1) {
            $items = $builder->get();
        } else {
            /**
             * Recupero i dati già impaginati da Laravel
             */
            $paginate = $builder->paginate($perPage);

            /**
             * Contiene la navigazione della paginazione fatta in Bootstrap 5.
             * Vedi: ~/app/Providers/AppServiceProvider.php@boot()
             */
            $pagination = $paginate->links()->toHtml();
        }

        return view('back.guestbook.datagrid', [
            'items' => $items ?? $paginate?->items() ?? [],
            'pagination' => $pagination ?? '',
        ])->render();
    }

    /**
     * Elaboro il form per la login
     *
     * @return string
     */
    private function renderLogin(): string
    {
        return view('back.login')
            ->render();
    }
}
