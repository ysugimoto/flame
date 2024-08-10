<?php

namespace Flame\Enums;

/**
 * Enum for the method how we should retrieve manifest file.
 *
 * @enum FetchMode
 */
enum FetchMode
{
    /**
     * LOCAL means find and retrieve manifest file on the local filesystem.
     */
    case LOCAL;

    /**
     * HTTP means find and retrieve manifest file via HTTP Request.
     * This value is suitable for storeing manifest on AWS S3, Google Cloud Storate, etc.
     */
    case HTTP;
}
