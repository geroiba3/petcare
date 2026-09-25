<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

class ForoController extends Controller
{
    private array $categorias = [
        'general' => 'General',
        'salud' => 'Salud',
        'alimentacion' => 'Alimentación',
        'comportamiento' => 'Comportamiento',
    ];

    public function index(Request $request)
    {
        $categoria = $request->query('categoria', 'general');

        if (!array_key_exists($categoria, $this->categorias)) {
            $categoria = 'general';
        }

        $db = Firebase::database();
        $posts = $db->getReference('foro/' . $categoria)->getValue() ?? [];

        if ($posts) {
            uasort($posts, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
        }

        return view('foro.index', [
            'posts' => $posts,
            'categoria' => $categoria,
            'categorias' => $this->categorias,
        ]);
    }

    public function create(Request $request)
    {
        $categoria = $request->query('categoria', 'general');

        return view('foro.create', [
            'categoria' => $categoria,
            'categorias' => $this->categorias,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria' => 'required|in:general,salud,alimentacion,comportamiento',
            'titulo' => 'required|string|max:150',
            'contenido' => 'required|string|max:2000',
        ]);

        $db = Firebase::database();
        $user = auth()->user();

        $ref = $db->getReference('foro/' . $data['categoria'])->push([
            'titulo' => $data['titulo'],
            'contenido' => $data['contenido'],
            'autor_id' => $user->id,
            'autor_nombre' => $user->name,
            'autor_rol' => $user->role,
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect()->route('foro.show', ['categoria' => $data['categoria'], 'postId' => $ref->getKey()])
            ->with('success', '¡Publicación creada!');
    }

    public function show($categoria, $postId)
    {
        $db = Firebase::database();
        $post = $db->getReference('foro/' . $categoria . '/' . $postId)->getValue();

        if (!$post) {
            return redirect()->route('foro.index')->with('error', 'Publicación no encontrada.');
        }

        $respuestas = $db->getReference('foro_respuestas/' . $postId)->getValue() ?? [];

        if ($respuestas) {
            uasort($respuestas, fn($a, $b) => strcmp($a['created_at'], $b['created_at']));
        }

        return view('foro.show', [
            'post' => $post,
            'postId' => $postId,
            'categoria' => $categoria,
            'categorias' => $this->categorias,
            'respuestas' => $respuestas,
        ]);
    }

    public function responder(Request $request, $categoria, $postId)
    {
        $data = $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $db = Firebase::database();
        $user = auth()->user();

        $db->getReference('foro_respuestas/' . $postId)->push([
            'contenido' => $data['contenido'],
            'autor_id' => $user->id,
            'autor_nombre' => $user->name,
            'autor_rol' => $user->role,
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect()->route('foro.show', ['categoria' => $categoria, 'postId' => $postId])
            ->with('success', 'Respuesta agregada.');
    }

    public function destroyPost($categoria, $postId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $db = Firebase::database();
        $db->getReference('foro/' . $categoria . '/' . $postId)->remove();
        $db->getReference('foro_respuestas/' . $postId)->remove();

        return redirect()->route('foro.index', ['categoria' => $categoria])->with('success', 'Publicación eliminada.');
    }

    public function destroyRespuesta($categoria, $postId, $respuestaId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $db = Firebase::database();
        $db->getReference('foro_respuestas/' . $postId . '/' . $respuestaId)->remove();

        return redirect()->route('foro.show', ['categoria' => $categoria, 'postId' => $postId])->with('success', 'Respuesta eliminada.');
    }
}
