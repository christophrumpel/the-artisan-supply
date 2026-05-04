# The Artisan Supply

Demo Laravel app for the YouTube video about building AI-assisted product and support workflows.

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

Run the app locally:

```bash
composer run dev
```

## AI Configuration

The demo uses Laravel AI with OpenAI as the default provider for chat, transcription, embeddings, and image generation. Add your own key to `.env` if you want to run the AI-backed flows:

```dotenv
OPENAI_API_KEY=
```

No real API keys or local environment files should be committed. `.env`, generated uploads, `vendor`, `node_modules`, IDE metadata, and local agent/editor files are ignored.

## Controller Structure

The demo routes use invokable controllers so each route has a small, focused entry point:

- `App\Http\Controllers\Shop\*`
- `App\Http\Controllers\Dashboard\*`
- `App\Http\Controllers\SupportVoiceMessageController`
