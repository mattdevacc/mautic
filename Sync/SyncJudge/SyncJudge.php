<?php

/*
 * @copyright   2018 Mautic Inc. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://www.mautic.com
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\IntegrationsBundle\Sync\SyncJudge;

use MauticPlugin\IntegrationsBundle\Sync\DAO\Sync\InformationChangeRequestDAO;
use MauticPlugin\IntegrationsBundle\Sync\Exception\ConflictUnresolvedException;
use MauticPlugin\IntegrationsBundle\Sync\SyncJudge\Modes\BestEvidence;
use MauticPlugin\IntegrationsBundle\Sync\SyncJudge\Modes\HardEvidence;
use MauticPlugin\IntegrationsBundle\Sync\SyncJudge\Modes\FuzzyEvidence;

/**
 * Class SyncJudge
 */
final class SyncJudge implements SyncJudgeInterface
{
    /**
     * @param string                      $mode
     * @param InformationChangeRequestDAO $leftChangeRequest
     * @param InformationChangeRequestDAO $rightChangeRequest
     *
     * @return InformationChangeRequestDAO
     * @throws ConflictUnresolvedException
     */
    public function adjudicate(
        $mode = self::PRESUMPTION_OF_INNOCENCE_MODE,
        InformationChangeRequestDAO $leftChangeRequest,
        InformationChangeRequestDAO $rightChangeRequest
    ) {
        if ($leftChangeRequest->getNewValue() === $rightChangeRequest->getNewValue()) {
            return $leftChangeRequest;
        }

        switch ($mode) {
            case SyncJudgeInterface::HARD_EVIDENCE_MODE:
                return HardEvidence::adjudicate($leftChangeRequest, $rightChangeRequest);
            case SyncJudgeInterface::BEST_EVIDENCE_MODE:
                return BestEvidence::adjudicate($leftChangeRequest, $rightChangeRequest);
            default:
                return FuzzyEvidence::adjudicate($leftChangeRequest, $rightChangeRequest);
        }
    }
}
