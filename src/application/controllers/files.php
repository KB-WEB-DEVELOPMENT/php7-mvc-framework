<?php
declare(strict_types=1);

use Shared\Controllers\Controller as Controller;
use Fonts\Proxy\Proxy as Proxy;
use Fonts\Types\Types as Type;
use Imagine\Gd\Imagine as Imagine;
use Imagine\Image\Box as Box;
use Imagine\Image\ImageInterface as ImageInterface;


class Files extends Controller
{
    public function fonts(string $name): void
    {
        $path = "/fonts";

        if (!file_exists("{$path}/{$name}"))
        {
            $proxy = new Proxy();

            $proxy->addFontTypes("{$name}", array(
                Types::OTF => "{$path}/{$name}.otf",
                Types::EOT => "{$path}/{$name}.eot",
                Types::TTF => "{$path}/{$name}.ttf"
            ));

            $weight = "";
            $style = "";
            $font = explode("-", $name);

            if (sizeof($font) > 1)
            {
                switch (strtolower($font[1]))
                {
                    case "Bold":
                        $weight = "bold";
                        break;
                    case "Oblique":
                        $style = "oblique";
                        break;
                    case "BoldOblique":
                        $weight = "bold";
                        $style = "oblique";
                        break;
                }
            }

            $declarations = "";
            $font = join("-", $font);
            $sniff = $proxy->sniff($_SERVER["HTTP_USER_AGENT"]);
            $served = $proxy->serve($font, $_SERVER["HTTP_USER_AGENT"]);

            if (sizeof($served) > 0)
            {
                $keys = array_keys($served);
                $declarations .= "@font-face {";
                $declarations .= "font-family: \"{$font}\";";

                $weight ?? $declarations .= "font-weight: {$weight};";

                $style  ??  $declarations .= "font-style: {$style};";

                $type = $keys[0];
                $url = $served[$type];

                ( $sniff && strtolower($sniff["browser"]) == "ie" )  ? $declarations .= "src: url(\"{$url}\");" : $declarations .= "src: url(\"{$url}\") format(\"{$type}\");";

                $declarations .= "}";
            }

            header("Content-type: text/css");

            $declarations ? echo $declarations : echo "/* no fonts to show */";

            $this->willRenderLayoutView = false;
            $this->willRenderActionView = false;
        }
        else
        {
            header("Location: {$path}/{$name}");
        }
    }

    public function thumbnails(int $id): void
    {
        $path = APP_PATH."/public/uploads";

        $file = File::first(array(
            "id = ?" => $id
        ));

        if ($file)
        {
            $width = 64;
            $height = 64;

            $name = $file->name;
            $filename = pathinfo($name, PATHINFO_FILENAME);
            $extension = pathinfo($name, PATHINFO_EXTENSION);

            if ($filename && $extension)
            {
                $thumbnail = "{$filename}-{$width}x{$height}.{$extension}";

                if (!file_exists("{$path}/{$thumbnail}"))
                {
                    $imagine = new Imagine();

                    $size = new Box($width, $height);
                    $mode = ImageInterface::THUMBNAIL_OUTBOUND;

                    $imagine
                        ->open("{$path}/{$name}")
                        ->thumbnail($size, $mode)
                        ->save("{$path}/{$thumbnail}");
                }

                header("Location: /uploads/{$thumbnail}");
                exit();
            }

            header("Location: /uploads/{$name}");
            exit();
        }
    }

    /**
    * @before _secure, _admin
    */
    public function view(): void
    {
        $this->actionView->set("files", File::all());
    }

    /**
    * @before _secure, _admin
    */
    public function delete(int $id): void
    {
        $file = File::first(array(
            "id = ?" => $id
        ));

        if ($file)
        {
            $file->deleted = true;
            $file->save();
        }

        self::redirect("/files/view.html");
    }

    /**
    * @before _secure, _admin
    */
    public function undelete(int $id): void
    {
        $file = File::first(array(
            "id = ?" => $id
        ));

        if ($file)
        {
            $file->deleted = false;
            $file->save();
        }

        self::redirect("/files/view.html");
    }

}
