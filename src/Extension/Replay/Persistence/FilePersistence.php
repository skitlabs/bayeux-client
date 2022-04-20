<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Extension\Replay\Persistence;

class FilePersistence implements Persistence
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function write(string $channel, int $replayId) : void
    {
        $data = $this->readFile();

        $data[$channel] = $replayId;

        $this->writeFile($data);
    }

    public function read(string $channel, int $default) : int
    {
        return $this->readFile()[$channel] ?? $default;
    }

    private function readFile() : array
    {
        if (! file_exists($this->filePath)) {
            return [];
        }

        return json_decode(file_get_contents($this->filePath), true, 512, JSON_THROW_ON_ERROR);
    }

    private function writeFile(array $data) : void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }
}
