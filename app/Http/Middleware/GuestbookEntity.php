<?php

namespace App\Http\Middleware;

use App\Models\Form;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class GuestbookEntity
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route()->parameters()['id'];

        if (!empty($id)) {
            /**
             * @var \Illuminate\Database\Eloquent\Builder $builder
             */
            $builder = Form::where('id', $id);

            /**
             * @var Form | null $item
             */
            Session::put("form-{$id}", $builder->first());
        }

        return $next($request);
    }
}
