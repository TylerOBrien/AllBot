<?php

namespace Database\Seeders;

use App\Models\{ Account, Channel, ChannelViewer, Game, Stream, StreamViewer, User, Viewer};

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $game = Game::create(['name' => 'Gaiden', 'directory_uri' => 'foo']);
        $user = User::create(['account_id' => Account::create()->id]);
        $channel = Channel::create(['user_id' => $user->id, 'name' => 'beardstrength']);
        $stream = Stream::create(['channel_id' => $channel->id, 'title' => 'Test', 'game_id' => $game->id]);
        $viewer = Viewer::create(['twitch_id' => 'abc123']);

        ChannelViewer::create([
            'channel_id' => $channel->id,
            'viewer_id' => $viewer->id,
        ]);

        StreamViewer::create([
            'stream_id' => $stream->id,
            'viewer_id' => $viewer->id,
        ]);
    }
}
