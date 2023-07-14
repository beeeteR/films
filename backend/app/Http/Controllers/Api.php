<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Api extends Controller
{
    // METHOD GET

    public function getUserById(Request $request)
    {
        $id = $request->id;

        return DB::table('users')->where('id', $id)->get();
    }
    public function authUser(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = DB::table('users')->where('email', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                return $user->remember_token;
            } else {
                return 'password error';
            }
        } else {
            return 'email not exist';
        }
    }
    public function getUserByToken(Request $request)
    {
        $token = $request->token;

        $user = DB::table('users')
            ->where('remember_token', $token)
            ->first();

        $watch_later = DB::table('watch_later')
            ->where('id_user', $user->id)
            ->select('id_kp', 'poster_url', 'name', 'rating', 'type', 'year')
            ->get();

        $arrayWatchLater = [];
        foreach ($watch_later as $key) {
            array_push($arrayWatchLater, ['kp' => $key->id_kp, 'posterUrl' => $key->poster_url, 'name' => $key->name, 'rating' => $key->rating, 'type' => $key->type, 'year' => $key->year]);
        }

        $playlists = $this->getUserPlaylists($request, $user->id);

        return ['user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email], 'watch_later' => $arrayWatchLater, 'playlists' => $playlists];
    }
    public function getComments(Request $request)
    {
        function getFirstParrentId($comment)
        {
            if ($comment->is_answer) {
                return getFirstParrentId(DB::table('commentaries')->where('id', $comment->answer_to_id)->first());
            } else {
                return $comment->id;
            }
        }

        $film_id = $request->film_id;

        // Разбиение на комменты и ответы

        if (DB::table('commentaries')->where('id_film', $film_id)->exists()) {
            $comments = DB::table('commentaries')
                ->where('id_film', $film_id)
                ->orderByDesc('datetime')
                ->get();

            $comment_with_answer = [];
            $answers = [];
            foreach ($comments as $key) {
                if ($key->is_answer) {
                    array_push($answers, [
                        'id_parent' => getFirstParrentId($key),
                        'id' => $key->id,
                        'id_film' => $key->id_film,
                        'id_user' => $key->id_user,
                        'user_name' => DB::table('users')
                            ->where('id', $key->id_user)
                            ->select('id', 'name')
                            ->first()
                            ->name,
                        'datetime' => $key->datetime,
                        'is_answer' => $key->is_answer,
                        'answer_to_id' => $key->answer_to_id,
                        'text' => $key->text
                    ]);
                } else {
                    array_push($comment_with_answer, [
                        'id' => $key->id,
                        'id_film' => $key->id_film,
                        'id_user' => $key->id_user,
                        'user_name' => DB::table('users')
                            ->where('id', $key->id_user)
                            ->select('id', 'name')
                            ->first()
                            ->name,
                        'datetime' => $key->datetime,
                        'text' => $key->text,
                        'answers' => []
                    ]);
                }
            }

            // Слияние комментов и ответов в один массив

            foreach (array_reverse($answers) as $answer) {
                foreach($comment_with_answer as $key => $comment) {
                    if ($comment['id'] == $answer['id_parent']) {
                        array_push($comment_with_answer[$key]['answers'], $answer);
                    }
                }
            }

            return $comment_with_answer;
        } else {
            return 0;
        }
    }
    public function getUserPlaylists(Request $request, $userID = null)
    {
        if ($userID) {
            $user_id = $userID;
        } else {
            $user_id = $request->user_id;
        }

        $playlists = DB::table('playlists')
            ->where('user_id', $user_id)
            ->get();
        if (count($playlists) != 0) {
            $answer = [];

            foreach ($playlists as $playlist_index => $playlist) {
                $films = DB::table('films_in_playlist')
                    ->where('playlist_id', $playlist->id)
                    ->get();

                array_push($answer, ['playlist_id' => $playlist->id, 'name' => $playlist->name, 'films' => $films]);
            }
            return $answer;
        } else {
            return null;
        }
    }

    // METHOD POST

    // COMMENTS

    public function setComment(Request $request)
    {
        $id_user = $request->input('id_user');
        $id_film = $request->input('id_film');
        $datetime = $request->input('datetime');
        $is_answer = $request->input('is_answer');
        $answer_to_id = $request->input('answer_to_id');
        $text = $request->input('text');

        DB::table('commentaries')
            ->insert([
                'id_film' => $id_film,
                'id_user' => $id_user,
                'datetime' => $datetime,
                'is_answer' => $is_answer,
                'answer_to_id' => $answer_to_id == 'null' ? null : $answer_to_id,
                'text' => $text
            ]);

        return 'OK';
    }
    public function delComment(Request $request)
    {
        function delAnswers($comment)
        {
            $answers = DB::table('commentaries')
                ->where('answer_to_id', $comment->id)
                ->get();

            foreach ($answers as $answer) {
                delAnswers($answer);
                DB::table('commentaries')->where('id', $answer->id)->delete();
            }
        }

        $id = $request->input('id');
        $comment = DB::table('commentaries')
            ->where('id', $id)
            ->first();

        delAnswers($comment);
        DB::table('commentaries')->where('id', $comment->id)->delete();

        return 'OK';
    }
    public function editComment(Request $request)
    {
        $id = $request->input('id');
        $text = $request->input('text');

        DB::table('commentaries')
            ->where('id', $id)
            ->update([
                'text' => $text
            ]);
        return 'OK';
    }

    // USER TABLE

    public function createUser(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $token = Str::random(50);

        $checkEmail = true;
        $emails = DB::table('users')->get('email');

        foreach ($emails as $key) {
            if ($key->email == $email) {
                $checkEmail = false;
            }
        }

        if ($checkEmail) {
            DB::table('users')
                ->insert([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'remember_token' => $token
                ]);
            return $token;
        } else {
            return 'email exist';
        }
    }

    // PLAYLISTS TABLES

    public function setPlaylist(Request $request)
    {
        $user_id = $request->input('user_id');
        $name = $request->input('name');

        DB::table('playlists')
            ->insert([
                'user_id' => $user_id,
                'name' => $name
            ]);
        return 'OK';
    }
    public function delPlaylist(Request $request)
    {
        $playlist_id = $request->input('playlist_id');

        DB::table('playlists')
            ->where('id', $playlist_id)
            ->delete();

        DB::table('films_in_playlist')
            ->where('playlist_id', $playlist_id)
            ->delete();

        return 'OK';
    }
    public function updateNamePlaylist(Request $request)
    {
        $playlist_id = $request->input('playlist_id');
        $name = $request->input('name');

        DB::table('playlists')
            ->where('id', $playlist_id)
            ->update([
                'name' => $name
            ]);
        return 'OK';
    }
    public function setFilmInPlaylist(Request $request)
    {
        $playlist_id = $request->input('playlist_id');
        $id_kp = $request->input('id_kp');
        $poster_url = $request->input('poster_url');
        $name = $request->input('name');
        $rating = $request->input('rating');
        $type = $request->input('type');
        $year = $request->input('year');
        $is_serial = $request->input('is_serial');
        $season = null;
        $episode = null;
        $studio = $request->input('studio');

        if ($is_serial) {
            $season = $request->input('season');
            $episode = $request->input('episode');
        }

        $film_id = DB::table('films_in_playlist')
            ->insertGetId([
                'playlist_id' => $playlist_id,
                'id_kp' => $id_kp,
                'poster_url' => $poster_url,
                'name' => $name,
                'rating' => $rating,
                'type' => $type,
                'year' => $year,
                'is_serial' => $is_serial,
                'studio' => $studio
            ]);

        if ($is_serial) {
            DB::table('films_in_playlist')
                ->where('id', $film_id)
                ->update([
                    'season' => $season,
                    'episode' => $episode
                ]);
        }

        return $film_id;
    }
    public function delFilmFromPlaylist(Request $request)
    {
        $film_id = $request->input('film_id');

        DB::table('films_in_playlist')
            ->where('id', $film_id)
            ->delete();
        return 'OK';
    }
    public function updateFilmInPlaylist(Request $request)
    {
        $film_id = $request->input('film_id');
        $season = $request->input('season');
        $episode = $request->input('episode');

        DB::table('films_in_playlist')
            ->where('id', $film_id)
            ->update([
                'season' => $season,
                'episode' => $episode
            ]);
        return 'OK';
    }
    public function moveFilmToAnotherPlaylist(Request $request)
    {
        $film_id = $request->input('film_id');
        $playlist_id = $request->input('playlist_id');

        DB::table('films_in_playlist')
            ->where('id', $film_id)
            ->update([
                'playlist_id' => $playlist_id
            ]);
        return 'OK';
    }

    // WATCH_LATER TABLE

    public function setUserWatchLater(Request $request)
    {
        $id_user = $request->input('id_user');
        $id_kp = $request->input('id_kp');
        $poster_url =  $request->input('poster_url');
        $name = $request->input('name');
        $rating = $request->input('rating');
        $type = $request->input('type');
        $year = $request->input('year');

        DB::table('watch_later')
            ->insert([
                'id_user' => $id_user,
                'id_kp' => $id_kp,
                'poster_url' => $poster_url,
                'name' => $name,
                'rating' => $rating,
                'type' => $type,
                'year' => $year
            ]);

        return 'OK';
    }
    public function delUserWatchLater(Request $request)
    {
        $id_user = $request->input('id_user');
        $id_kp = $request->input('id_kp');

        DB::table('watch_later')
            ->where('id_user', $id_user)
            ->where('id_kp', $id_kp)
            ->delete();

        return 'OK';
    }
}
