<?php

namespace Elisa\ProductApi\Model\Event;

use Elisa\ProductApi\Exception\ElisaException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\Client\CurlFactory;

class ImageImporter
{
    /** @var DirectoryList */
    protected $directoryList;
    /** @var File */
    protected $fileIo;
    /** @var Curl|null */
    protected $httpClient = null;
    /** @var CurlFactory */
    protected $httpClientFactory;
    /** @var string[] */
    protected $directoryFiles = [];

    /**
     * @param DirectoryList $directoryList
     * @param CurlFactory $httpClientFactory
     * @param File $fileIo
     */
    public function __construct(
        DirectoryList $directoryList,
        CurlFactory $httpClientFactory,
        File $fileIo
    ) {
        $this->directoryList = $directoryList;
        $this->httpClientFactory = $httpClientFactory;
        $this->fileIo = $fileIo;
    }

    /**
     * Get directory file contents
     *
     * @param string $dir
     * @return string[]
     * @throws LocalizedException
     */
    private function getDirectoryFiles(string $dir): array
    {
        if (!isset($this->directoryFiles[$dir])) {
            $this->fileIo->cd($dir);
            $this->directoryFiles[$dir] = array_reduce(
                $this->fileIo->ls(File::GREP_FILES),
                function ($carry, $lsEntry) {
                    $fileName = $lsEntry['text'];
                    $fileNameParts = explode('.', $fileName);
                    if (count($fileNameParts) > 1) {
                        array_pop($fileNameParts);
                    }
                    $noExtFileName = implode('.', $fileNameParts);
                    $carry[$noExtFileName] = $fileName;
                    return $carry;
                },
                []
            );
        }

        return $this->directoryFiles[$dir];
    }

    /**
     * @param string[] $validPaths
     * @return void
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function cleanup(array $validPaths)
    {
        $relativeFolderPath = DIRECTORY_SEPARATOR . 'elisa' . DIRECTORY_SEPARATOR . 'events';

        $absoluteFolderPath = $this->directoryList->getPath(DirectoryList::MEDIA) . $relativeFolderPath;

        if (!$this->fileIo->fileExists($absoluteFolderPath, false)) {
            $this->fileIo->mkdir($absoluteFolderPath);
        }

        $files = $this->getDirectoryFiles($absoluteFolderPath);

        foreach ($validPaths as $validPath) {
            $fileNameParts = explode(DIRECTORY_SEPARATOR, $validPath);
            $fileName = end($fileNameParts);
            $existingKey =  array_search($fileName, $files);

            if ($existingKey !== false) {
                unset($files[$existingKey]);
            }
        }

        foreach ($files as $file) {
            $this->fileIo->rm($absoluteFolderPath . DIRECTORY_SEPARATOR . $file);
        }
    }

    /**
     * Returns relative media path for imported image
     *
     * @param string $imageUrl
     * @param string $prefix
     * @return string
     * @throws ElisaException
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function importImage(string $imageUrl, string $prefix): string
    {
        $relativeFolderPath = DIRECTORY_SEPARATOR . 'elisa' . DIRECTORY_SEPARATOR . 'events';

        $absoluteFolderPath = $this->directoryList->getPath(DirectoryList::MEDIA) . $relativeFolderPath;

        if (!$this->fileIo->fileExists($absoluteFolderPath, false)) {
            $this->fileIo->mkdir($absoluteFolderPath);
        }

        try {
            $uri = \Laminas\Uri\UriFactory::factory($imageUrl);
        } catch (\Throwable $e) {
            if (!class_exists(\Zend\Uri\UriFactory::class)) {
                throw $e;
            }
            $uri = \Zend\Uri\UriFactory::factory($imageUrl);
        }

        $uriPath = $uri->getPath();
        $saveFileName = $prefix . '-' . md5($uriPath); //phpcs:ignore

        $files = $this->getDirectoryFiles($absoluteFolderPath);

        if (isset($files[$saveFileName])) {
            return $relativeFolderPath . DIRECTORY_SEPARATOR . $files[$saveFileName];
        }

        [$fileContents, $contentType] = $this->getRemoteContent($imageUrl);

        if (!$fileContents) {
            throw new ElisaException(__("Remote File '%1' is empty.", $imageUrl));
        }

        $absoluteFilePath = $absoluteFolderPath . DIRECTORY_SEPARATOR . $saveFileName;

        if ($extension = $this->mime2ext($contentType)) {
            $this->fileIo->write($absoluteFilePath . ".$extension", $fileContents);
            return $relativeFolderPath . DIRECTORY_SEPARATOR . $saveFileName . ".$extension";
        }

        $this->fileIo->write($absoluteFilePath, $fileContents);
        return $relativeFolderPath . DIRECTORY_SEPARATOR . $saveFileName;
    }

    /**
     * Returns HTTP client
     *
     * @return Curl
     */
    private function getHttpClient(): Curl
    {
        if ($this->httpClient !== null) {
            return $this->httpClient;
        }

        $this->httpClient = $this->httpClientFactory->create();
        $this->httpClient->setOption(CURLOPT_FOLLOWLOCATION, true);
        return $this->httpClient;
    }

    /**
     * Returns body and content-type response for given URL
     *
     * @param string $url
     * @return string[]
     */
    private function getRemoteContent(string $url): array
    {
        $client = $this->getHttpClient();
        $client->get($url);
        $status = $client->getStatus();

        if ($status >= 300) {
            return ['', ''];
        }

        $headers = $client->getHeaders();
        $contentType = $headers['content-type'] ?? '';
        return [$client->getBody(), $contentType];
    }

    /**
     * Get image extension from content/mime type
     *
     * @param string $mime
     * @return string|null
     */
    private function mime2ext(string $mime): ?string
    {
        $mimeMap = [
            'application/bmp' => 'bmp',
            'application/x-bmp' => 'bmp',
            'image/bmp' => 'bmp',
            'image/cdr' => 'cdr',
            'image/gif' => 'gif',
            'image/jp2' => 'jp2',
            'image/jpeg' => 'jpeg',
            'image/jpm' => 'jp2',
            'image/jpx' => 'jp2',
            'image/ms-bmp' => 'bmp',
            'image/pjpeg' => 'jpeg',
            'image/png' => 'png',
            'image/svg+xml' => 'svg',
            'image/tiff' => 'tiff',
            'image/vnd.adobe.photoshop' => 'psd',
            'image/vnd.microsoft.icon' => 'ico',
            'image/webp' => 'webp',
            'image/x-bitmap' => 'bmp',
            'image/x-bmp' => 'bmp',
            'image/x-cdr' => 'cdr',
            'image/x-ico' => 'ico',
            'image/x-icon' => 'ico',
            'image/x-ms-bmp' => 'bmp',
            'image/x-png' => 'png',
            'image/x-win-bitmap' => 'bmp',
            'image/x-windows-bmp' => 'bmp',
            'image/x-xbitmap' => 'bmp',
        ];

        return $mimeMap[$mime] ?? null;
    }
}
