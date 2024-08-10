<?php

declare(strict_types=1);

namespace Tests\Fixture;

class Manifest
{
    public static function getFixture(): string
    {

        return <<<EOS
{
  "manifest": {
    "src/assets/react.svg": {
      "file": "assets/react-CHdo91hT.svg",
      "src": "src/assets/react.svg"
    },
    "src/main.css": {
      "file": "assets/main-DtxBo3lp.css",
      "src": "src/main.css",
      "isEntry": true
    },
    "src/main.tsx": {
      "file": "assets/main-BTkHr7m7.js",
      "name": "main",
      "src": "src/main.tsx",
      "isEntry": true,
      "css": [
        "assets/main-DiwrgTda.css"
      ],
      "assets": [
        "assets/react-CHdo91hT.svg"
      ]
    }
  },
  "aliases": {
    "@main": "src/main.tsx",
    "@css": "src/main.css"
  }
}
EOS;
    }
}
