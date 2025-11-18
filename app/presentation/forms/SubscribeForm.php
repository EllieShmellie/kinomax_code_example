<?php

declare(strict_types=1);

namespace app\presentation\forms;

use app\application\usecases\SubscribeToPremiereHandler;
use Throwable;
use yii\base\Model;

final class SubscribeForm extends Model
{
    public string $email = '';
    public string $timezone = '';
    public string $release_id = '';

    /**
     * @param int[] $availablePremieres
     */
    public function __construct(
        private readonly SubscribeToPremiereHandler $handler,
        private array $availablePremieres = [],
        $config = []
    ) {
        parent::__construct($config);
        $this->availablePremieres = array_map('intval', $this->availablePremieres);
    }

    public function rules(): array
    {
        return [
            [['email', 'timezone', 'release_id'], 'required'],
            ['email', 'email'],
            ['timezone', 'in', 'range' => array_keys(self::timezoneOptions())],
            ['release_id', 'integer'],
            ['release_id', 'in', 'range' => $this->availablePremieres],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'timezone' => 'Часовой пояс',
            'release_id' => 'Премьера',
        ];
    }

    public function subscribe(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        try {
            $this->handler->handle($this->email, $this->timezone, (int) $this->release_id);
            return true;
        } catch (Throwable) {
            $this->addError('release_id', 'Не удалось оформить подписку.');
            return false;
        }
    }

    public static function timezoneOptions(): array
    {
        return [
            'Europe/Moscow' => 'Москва (UTC+3)',
            'Europe/London' => 'Лондон (UTC+0)',
            'Asia/Yekaterinburg' => 'Екатеринбург (UTC+5)',
            'Asia/Vladivostok' => 'Владивосток (UTC+10)',
            'America/New_York' => 'Нью-Йорк (UTC-5)',
        ];
    }
}
