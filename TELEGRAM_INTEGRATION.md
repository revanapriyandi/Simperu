# Telegram Integration for SIMPERU

This document explains how the Telegram integration works in the SIMPERU system for the resident panel.

## Overview

The Telegram integration ensures that residents must link their Telegram account before they can access the resident panel. This enables the system to send important notifications via Telegram.

## Features

-   **Automatic Check**: Middleware automatically checks if residents have linked their Telegram account
-   **User-Friendly Linking**: Easy-to-follow linking process with visual instructions
-   **Secure**: Uses base64 encoded user IDs for secure linking
-   **Notifications**: Send welcome messages and system notifications
-   **Management**: Ability to unlink accounts when needed

## Files Created/Modified

### New Files

1. `app/Http/Middleware/CheckTelegramLinked.php` - Middleware to check Telegram linking
2. `app/Http/Controllers/TelegramController.php` - Handles Telegram operations
3. `app/Services/TelegramService.php` - Service class for Telegram API operations
4. `resources/views/telegram/link.blade.php` - Telegram linking page
5. `app/Console/Commands/SetupTelegramWebhook.php` - Command to setup webhook
6. `.env.telegram.example` - Example environment variables

### Modified Files

1. `bootstrap/app.php` - Registered the middleware
2. `app/Providers/Filament/ResidentPanelProvider.php` - Added middleware to resident panel
3. `app/Models/User.php` - Added telegram_chat_id to fillable fields
4. `config/services.php` - Added Telegram configuration
5. `routes/web.php` - Added Telegram routes

## Setup Instructions

### 1. Create Telegram Bot

1. Message @BotFather on Telegram
2. Use `/newbot` command
3. Follow instructions to create your bot
4. Save the bot token and username

### 2. Environment Configuration

Add these variables to your `.env` file:

```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_BOT_USERNAME=your_bot_username_here
TELEGRAM_WEBHOOK_URL=https://yourdomain.com/telegram/webhook
```

### 3. Database Migration

The `telegram_chat_id` field is already added to users table via migration:
`2025_05_05_074635_add_telegram_chat_id_to_users_table.php`

If not migrated yet, run:

```bash
php artisan migrate
```

### 4. Setup Webhook (Optional)

For production, set up the webhook:

```bash
php artisan telegram:setup-webhook --url=https://yourdomain.com/telegram/webhook
```

To remove webhook:

```bash
php artisan telegram:setup-webhook --remove
```

## How It Works

### 1. Middleware Check

-   When a resident accesses any page in the resident panel
-   `CheckTelegramLinked` middleware checks if `telegram_chat_id` is set
-   If not set, redirects to `/telegram/link` page

### 2. Linking Process

1. User is shown instructions on the linking page
2. User clicks "Buka Telegram" button
3. Opens Telegram with `/start {encoded_user_id}` command
4. Bot receives the command and links the account
5. User returns to the system and can continue

### 3. Verification

-   Users can check their linking status
-   Auto-refresh every 10 seconds on the linking page
-   Manual check button available

## API Endpoints

### GET /telegram/link

-   Shows the Telegram linking page
-   Requires authentication

### POST /telegram/webhook

-   Receives webhook from Telegram
-   Handles account linking

### GET /telegram/status

-   Returns JSON with linking status
-   Used for AJAX checks

### POST /telegram/unlink

-   Removes Telegram link from user account
-   Returns JSON response

## Security Considerations

1. **User ID Encoding**: User IDs are base64 encoded in the start parameter
2. **Role Verification**: Only users with 'resident' role can be linked
3. **Route Exclusions**: Telegram-related routes are excluded from the middleware
4. **Webhook Verification**: Basic verification implemented (can be enhanced)

## Usage in Code

### Send Notification to User

```php
use App\Services\TelegramService;

$telegramService = app(TelegramService::class);
$telegramService->sendNotificationToUser($user, 'Your message here');
```

### Send Bulk Notifications

```php
$userIds = [1, 2, 3, 4, 5];
$message = 'Bulk notification message';
$results = $telegramService->sendBulkNotifications($userIds, $message);
```

### Check if User Has Telegram

```php
$user = Auth::user();
$hasLinkedTelegram = !empty($user->telegram_chat_id);
```

## Troubleshooting

### Bot Not Responding

1. Check if `TELEGRAM_BOT_TOKEN` is correct
2. Verify bot is not blocked
3. Check webhook setup

### Users Not Being Redirected

1. Verify middleware is registered in `bootstrap/app.php`
2. Check if middleware is added to ResidentPanelProvider
3. Clear cache: `php artisan config:clear`

### Webhook Issues

1. Check webhook URL is accessible from internet
2. Verify SSL certificate is valid
3. Check webhook info: `php artisan telegram:setup-webhook --info`

## Future Enhancements

1. **Enhanced Security**: Implement webhook signature verification
2. **Rich Messages**: Support for buttons, images, and formatted messages
3. **Message Templates**: Create templates for different notification types
4. **Analytics**: Track message delivery and engagement
5. **Group Support**: Support for sending to Telegram groups

## Testing

For development/testing without actual Telegram bot:

1. Comment out the middleware in ResidentPanelProvider
2. Manually set `telegram_chat_id` for test users
3. Test the notification sending functionality

## Support

For issues related to Telegram integration:

1. Check Laravel logs in `storage/logs/`
2. Verify environment variables are set correctly
3. Test bot functionality directly in Telegram
4. Check network connectivity to Telegram API
