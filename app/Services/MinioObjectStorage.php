<?php

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Throwable;

class MinioObjectStorage
{
    /**
     * Upload a private object to MinIO via AWS SDK (no Flysystem adapter required).
     */
    public function putFile(string $objectKey, UploadedFile $file): string
    {
        $client = $this->client();
        $bucket = $this->bucket();
        $source = $file->getRealPath() ?: $file->getPathname();

        if ($source === false || $source === '' || ! is_readable($source)) {
            throw new RuntimeException('ไม่สามารถอ่านไฟล์ที่อัปโหลดได้');
        }

        $body = fopen($source, 'rb');
        if ($body === false) {
            throw new RuntimeException('ไม่สามารถเปิดไฟล์ที่อัปโหลดได้');
        }

        try {
            $client->putObject([
                'Bucket' => $bucket,
                'Key' => ltrim($objectKey, '/'),
                'Body' => $body,
                'ContentType' => $file->getMimeType()
                    ?: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ACL' => 'private',
            ]);
        } catch (Throwable $e) {
            throw new RuntimeException('อัปโหลดไฟล์ไป MinIO ไม่สำเร็จ: '.$e->getMessage(), 0, $e);
        } finally {
            if (is_resource($body)) {
                fclose($body);
            }
        }

        return ltrim($objectKey, '/');
    }

    public function client(): S3Client
    {
        if (! class_exists(S3Client::class)) {
            throw new RuntimeException('ไม่พบ aws/aws-sdk-php บนเซิร์ฟเวอร์ กรุณาติดตั้งด้วย composer require aws/aws-sdk-php');
        }

        $endpoint = $this->endpoint();
        if ($endpoint === null) {
            throw new RuntimeException('ยังไม่ได้ตั้งค่า MINIO_ENDPOINT');
        }

        $key = (string) config('minio.key', '');
        $secret = (string) config('minio.secret', '');
        if ($key === '' || $secret === '') {
            throw new RuntimeException('ยังไม่ได้ตั้งค่า MINIO_ACCESS_KEY / MINIO_SECRET_KEY');
        }

        return new S3Client([
            'version' => 'latest',
            'region' => (string) config('minio.region', 'us-east-1'),
            'endpoint' => $endpoint,
            'use_path_style_endpoint' => (bool) config('minio.use_path_style_endpoint', true),
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
            'http' => [
                'verify' => ! (bool) config('minio.insecure_skip_verify', false),
            ],
        ]);
    }

    public function bucket(): string
    {
        $bucket = trim((string) config('minio.bucket', 'sci-reg'));
        if ($bucket === '') {
            throw new RuntimeException('ยังไม่ได้ตั้งค่า MINIO_BUCKET');
        }

        return $bucket;
    }

    public function endpoint(): ?string
    {
        $host = trim((string) config('minio.endpoint', ''));
        if ($host === '') {
            return null;
        }

        if (str_starts_with($host, 'http://') || str_starts_with($host, 'https://')) {
            return $host;
        }

        $scheme = filter_var(config('minio.use_ssl', true), FILTER_VALIDATE_BOOLEAN) ? 'https' : 'http';
        $port = trim((string) config('minio.port', ''));

        return $port !== ''
            ? sprintf('%s://%s:%s', $scheme, $host, $port)
            : sprintf('%s://%s', $scheme, $host);
    }
}
