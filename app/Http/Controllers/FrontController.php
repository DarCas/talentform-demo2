<?php

namespace App\Http\Controllers;

use App\Enums\AlertType;
use App\Mail\GuestbookMail;
use App\Models\Form;
use App\View\AlertView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades;

class FrontController extends Controller
{
    /**
     * Metodo collegato alla route "/"
     *
     * @param Request $req
     *
     * @return Response
     */
    function index(Request $req): Response
    {
        /**
         * Se le operazioni di salvataggio e invio del form vanno a buon fine
         * invio in $_GET il parametro "success" valorizzato a "true" e stampo
         * un alert di Bootstrap
         */
        if ($req->get('success', false)) {
            $alertTemplate = new AlertView(
                message: 'Messaggio inviato con successo!',
                type: AlertType::Success,
                width: '100%',
            );
        }

        /**
         * Carico la index prendendo il template di blade "default.blade.php" e
         * imposto le variabili utilizzi al rendering del template.
         */
        $view = response()
            ->view('default', [
                'css' => [
                    "//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css",
                    "//cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css",
                    '/css/style.css',
                ],
                'js' => [
                    "//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js",
                ],
                'alertTemplate' => $alertTemplate ?? null,
                'errors' => Facades\Session::get('errors'),
                'formTemplate' => $this->renderForm(),
                'guestbookTemplate' => $this->renderGuestbook(),
                'year' => $this->year,
                'title' => $req->get('title', 'Guestbook'),
            ]);

        /**
         * Cancello dalla SESSION gli "errors"
         */
        Facades\Session::forget('errors');

        /**
         * Se le operazioni di salvataggio e invio del form vanno a buon fine
         * invio in $_GET il parametro "success" valorizzato a "true" imposto
         * un refresh della pagina dopo 5 secondi ricaricando la stessa pagina
         * ma senza valori di $_GET.
         */
        if ($req->get('success', false)) {
            $view->withHeaders([
                'Refresh' => '5; url=/',
            ]);
        }

        return $view;
    }

    /**
     * Metodo collegato alla route "/sendmail"
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    function sendmail(Request $request): RedirectResponse
    {
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
             * Reindirizzo alla index
             */
            return redirect('/');
        }

        /**
         * Scrivo un record nella tabella "form" del database con Eloquent.
         */
        $form = new Form();
        $form->nome = $validator->getValue('nome');
        $form->cognome = $validator->getValue('cognome');
        $form->email = $validator->getValue('email');
        $form->messaggio = $validator->getValue('messaggio');
        $form->save();

        /**
         * Invio e-mail con Facades.
         */
        $mail = Facades\Mail::to(new Address(
            address: config('mail.to.address'),
            name: config('mail.to.name')
        ));
        $mail->send(
            new GuestbookMail([
                'nome' => $validator->getValue('nome'),
                'cognome' => $validator->getValue('cognome'),
                'email' => $validator->getValue('email'),
                'messaggio' => $validator->getValue('messaggio'),
            ])
        );

        return redirect('/?success=true');
    }

    /**
     * Mi restituisce il form per inviare il messaggio elaborato
     *
     * @return string
     */
    private function renderForm(): string
    {
        /**
         * Renderizzo il template di blade "partials/form.blade.php"
         * passando i parametri utili alla renderizzazione.
         */
        $tpl = view('partials.form', [
            'data' => Facades\Session::get('data'),
        ])->render();

        /**
         * Cancello dalla SESSION "data"
         */
        Facades\Session::forget('data');

        return $tpl;
    }

    /**
     * Mi restituisce il guestbook con i messaggi ricevuti e la paginazione.
     *
     * @return string
     */
    private function renderGuestbook(): string
    {
        /**
         * Recupero tutti i dati dalla tabella del database "form" ordinati
         * per "data_ricezione" discendenti.
         *
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */
        $builder = Form::orderBy('data_ricezione', 'desc');

        /**
         * Recupero i dati già impaginati da Laravel
         */
        $paginate = $builder->paginate(
            perPage: 4, // Definisco quanti messaggi voglio visualizzare per pagina
        );

        /**
         * Contiene la navigazione della paginazione fatta in Bootstrap 5.
         * Vedi: ~/app/Providers/AppServiceProvider.php@boot()
         */
        $pagination = $paginate->links()->toHtml();

        return view('partials.guestbook', [
            'items' => $paginate->items(),
            'pagination' => $pagination,
        ])->render();
    }
}
