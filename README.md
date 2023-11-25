<h1 align="center">Welcome To PocketCord</h1>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-v8%2B-blue" />
  <img src="https://img.shields.io/badge/PocketMineMP-v5.0.0%2B-orange" />
</p>

PocketCord is a PocketmineMP plugin that allows you to broadcast in-game events to a Discord webhook. With PocketCord, you can easily keep your players updated with announcements and events, even when they are not online. 

## Features

- Broadcast in-game events using discord webhooks
- Customizable message formatting 
- Simple in game setup
- Supports multiple webhook URLs

## Configuration
**plugin_data/pocketcord/config.yml**
```yml
database-name: 'webhooks.db'
webhook-send-rate: 5

# {time}
start-format: "{time} => The server has started."
stop-format: "{time} => The server has stopped."

# {time} {player_name}
join-format: "{time} => {player_name} has joined the server."
leave-format: "{time} => {player_name} has left the server."
death-format: "{time} => {player_name} has died."

# {time} {player_name} {messsage}
chat-format: "{time} => {player_name} just said {message}."
command-format: "{time} => {player_name} just ran {message}."

# {time} {killer_name} {dead_name}
kill-format: "{time} => {killer_name} killed {dead_name}."
```

## Installation

To install PocketCord, follow these steps:

1. Download the latest version of PocketCord from the [GitHub repository](https://github.com/CJMustard1452/PocketCord/releases/tag/Newest).
2. Place the `PocketCord.phar` file in your server's `plugins` folder.
3. Restart your server to load the plugin.

## Usage

To set up PocketCord, you need to provide a Discord webhook URL. To do this, run the following command in-game:

```/pocketcord```

## Support

If you need help with PocketCord or have any questions, feel free to create an issue on this git!
