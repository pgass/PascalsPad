<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Entrie;
use App\Models\Padlet;
use App\Models\Rating;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;

class PadletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $padlet1 = new Padlet;
        $padlet1->name="Testpadlet";
        $padlet1->is_public=false;
        $padlet1->user_id=1;
        $padlet1->save();

        //adding entries to padlet
        $entrie1 = new Entrie;
        $entrie1->user_id = 1;
        $entrie1->title = "Das ist mein erster Entry.";
        $entrie1->content ="Alles funktioniert!";

        $entrie2 = new Entrie;
        $entrie2->user_id = 2;
        $entrie2->title = "Noch ein weiterer Entry";
        $entrie2->content ="Auch der funktioniert einwandfrei!";
        $padlet1->entries()->saveMany([$entrie1, $entrie2]);
        $padlet1->save();


        $padlet2 = new Padlet;
        $padlet2->name="Karli's erstes Padlet";
        $padlet2->is_public=true;
        $padlet2->user_id=2;
        $padlet2->save();

        $padlet3 = new Padlet;
        $padlet3->name="Prof. Schönböck's erstes Padlet";
        $padlet3->is_public=true;
        $padlet3->user_id=3;
        $padlet3->save();

        $comment1 = new Comment();
        $comment1->user_id = 1;
        $comment1->entrie_id = 1;
        $comment1->comment = 'Entry 1 ist super!';
        $comment1->save();

        $rating1 = new Rating();
        $rating1->user_id = 1;
        $rating1->entrie_id = 1;
        $rating1->rating = 4;
        $rating1->save();

        $entrie1->comments()->saveMany([$comment1]);
        $entrie1->ratings()->saveMany([$rating1]);
        $entrie1->save();
    }
}
