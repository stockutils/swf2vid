<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Swf2Vid {

    use Minute\Config\Config;
    use Minute\Routing\RouteEx;
    use Minute\View\Helper;
    use Minute\View\Redirection;
    use Minute\View\View;

    class FlashPlayer {
        /**
         * @var Config
         */
        private $config;

        /**
         * FlashPlayer constructor.
         *
         * @param Config $config
         */
        public function __construct(Config $config) {
            $this->config = $config;
        }

        public function index(int $project_id) {
            $host = $this->config->getPublicVars('host');
            $data = ['converter_url' => sprintf('//www.stockutils.com/static/local/flash/converter/converter.swf', $host),
                     'player_url' => sprintf('%s/static/local/flash/player/player.swf', $host, $project_id),
                     'project_url' => sprintf('%s/members/projects/data/%d', $host, $project_id)];

            if ($_GET['html'] ?? null == '0') {
                $url = sprintf('%s?%s', $data['converter_url'], http_build_query(array_intersect_key($data, array_flip(['player_url', 'project_url']))));

                return new Redirection($url);
            }

            return (new View('', $data, false));
        }
    }
}