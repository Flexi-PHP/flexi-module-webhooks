<?php

declare(strict_types=1);

namespace CubaDevOps\Flexi\Modules\WebHooks\Domain\Events;

use Flexi\Contracts\Interfaces\EventInterface;

final class WebhookEvent implements EventInterface
{
    private string $name;
    private string $fired_by;
    private array $data;
    private \DateTimeImmutable $occurred_on;
    private bool $propagation_stopped = false;

    public function __construct(string $name, string $fired_by, array $data = [])
    {
        if ('' === $name || '' === $fired_by) {
            throw new \InvalidArgumentException('Event name and fired_by are required.');
        }

        $this->name = $name;
        $this->fired_by = $fired_by;
        $this->data = $data;
        $this->occurred_on = new \DateTimeImmutable();
    }

    public static function fromArray(array $data): self
    {
        if (!self::validate($data)) {
            throw new \InvalidArgumentException('Invalid event data.');
        }

        return new self($data['event'], $data['fired_by'], $data['data'] ?? []);
    }

    public static function validate(array $data): bool
    {
        return isset($data['event'], $data['fired_by'])
            && is_string($data['event']) && '' !== $data['event']
            && is_string($data['fired_by']) && '' !== $data['fired_by']
            && (!isset($data['data']) || is_array($data['data']));
    }

    public function toArray(): array
    {
        return [
            'event' => $this->name,
            'fired_by' => $this->fired_by,
            'data' => $this->data,
            'occurred_on' => $this->occurred_on->format(DATE_ATOM),
        ];
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function get(string $name)
    {
        return $this->data[$name] ?? ($this->toArray()[$name] ?? null);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurred_on;
    }

    public function firedBy(): string
    {
        return $this->fired_by;
    }

    public function serialize(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function isPropagationStopped(): bool
    {
        return $this->propagation_stopped;
    }

    public function stopPropagation(): void
    {
        $this->propagation_stopped = true;
    }
}
