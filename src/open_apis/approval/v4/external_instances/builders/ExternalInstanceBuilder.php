<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\builders;

use InvalidArgumentException;
use feishu\open_apis\approval\v4\external_instances\enums\ExternalInstanceDisplayMethod;
use feishu\open_apis\approval\v4\external_instances\enums\ExternalInstanceStatus;
use feishu\open_apis\approval\v4\external_instances\enums\ExternalInstanceUpdateMode;
use feishu\open_apis\approval\v4\external_instances\ExternalInstancesCreateRequest;

final class ExternalInstanceBuilder
{
    private ?string $approvalCode = null;
    private ?ExternalInstanceStatus $status = null;
    private ?string $instanceId = null;
    private ?string $pcLink = null;
    private ?string $mobileLink = null;

    private string|int|null $startTime = null;
    private string|int|null $endTime = 0;
    private string|int|null $updateTime = null;

    /** @var array<int, array<string, mixed>> */
    private array $i18nResources = [];

    private ?string $userId = null;
    private ?string $openId = null;
    private ?string $title = null;
    private ?string $userName = null;
    private ?string $departmentId = null;
    private ?string $departmentName = null;

    private ExternalInstanceDisplayMethod $displayMethod = ExternalInstanceDisplayMethod::Browser;
    private ExternalInstanceUpdateMode $updateMode = ExternalInstanceUpdateMode::Replace;

    private ?string $extra = null;

    /** @var array<int, array{name:string,value:string}>|null */
    private ?array $form = null;

    /** @var array<int, array<string, mixed>>|null */
    private ?array $taskList = null;

    /** @var array<int, array<string, mixed>>|null */
    private ?array $ccList = null;

    private ?string $trusteeshipUrlToken = null;
    private ?string $trusteeshipUserIdType = null;

    /** @var array<string, string>|null */
    private ?array $trusteeshipUrls = null;

    /** @var array<string, mixed>|null */
    private ?array $trusteeshipCacheConfig = null;

    private ?string $resourceRegion = null;

    public static function make(): self
    {
        return new self();
    }

    public function approvalCode(string $approvalCode): self
    {
        $this->approvalCode = trim($approvalCode);

        return $this;
    }

    public function status(ExternalInstanceStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function instanceId(string|int $instanceId): self
    {
        $this->instanceId = (string) $instanceId;

        return $this;
    }

    public function links(string $pcLink, ?string $mobileLink = null): self
    {
        $this->pcLink = trim($pcLink);
        $this->mobileLink = $mobileLink === null ? null : trim($mobileLink);

        return $this;
    }

    public function pcLink(string $pcLink): self
    {
        return $this->links($pcLink);
    }

    public function mobileLink(?string $mobileLink): self
    {
        $this->mobileLink = $mobileLink === null ? null : trim($mobileLink);

        return $this;
    }

    public function startTime(string|int $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function endTime(string|int $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function updateTime(string|int $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function userId(?string $userId): self
    {
        $this->userId = $this->nullableString($userId);

        return $this;
    }

    public function openId(?string $openId): self
    {
        $this->openId = $this->nullableString($openId);

        return $this;
    }

    /** @param array<int, array<string, mixed>> $i18nResources */
    public function i18n(array $i18nResources): self
    {
        $this->i18nResources = $i18nResources;

        return $this;
    }

    /** @param array<string, string|int|float|null> $texts */
    public function zhCn(array $texts): self
    {
        $this->i18nResources = ExternalInstanceI18nBuilder::zhCn($texts);

        return $this;
    }

    public function title(string $i18nKey = ExternalInstanceI18nBuilder::TITLE): self
    {
        $this->title = ExternalInstanceI18nBuilder::key($i18nKey);

        return $this;
    }

    public function userName(string $i18nKey = ExternalInstanceI18nBuilder::USER_NAME): self
    {
        $this->userName = ExternalInstanceI18nBuilder::key($i18nKey);

        return $this;
    }

    public function departmentId(?string $departmentId): self
    {
        $this->departmentId = $this->nullableString($departmentId);

        return $this;
    }

    public function departmentName(string $i18nKey = ExternalInstanceI18nBuilder::DEPARTMENT_NAME): self
    {
        $this->departmentName = ExternalInstanceI18nBuilder::key($i18nKey);

        return $this;
    }

    public function displayMethod(ExternalInstanceDisplayMethod $displayMethod): self
    {
        $this->displayMethod = $displayMethod;

        return $this;
    }

    public function updateMode(ExternalInstanceUpdateMode $updateMode): self
    {
        $this->updateMode = $updateMode;

        return $this;
    }

    /** @param array<string, mixed>|string|null $extra */
    public function extra(array|string|null $extra): self
    {
        if ($extra === null || $extra === '') {
            $this->extra = null;

            return $this;
        }

        $this->extra = is_array($extra)
            ? json_encode($extra, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
            : $extra;

        return $this;
    }

    /** @param array<int, array{name:string,value:string}> $form */
    public function form(array $form): self
    {
        $this->form = ExternalInstanceFormBuilder::make($form);

        return $this;
    }

    /** @param array<int, array<string, mixed>> $taskList */
    public function taskList(array $taskList): self
    {
        ExternalInstanceTaskBuilder::assertListLimit($taskList);
        $this->taskList = $taskList;

        return $this;
    }

    /** @param array<int, array<string, mixed>> $ccList */
    public function ccList(array $ccList): self
    {
        ExternalInstanceCcBuilder::assertListLimit($ccList);
        $this->ccList = $ccList;

        return $this;
    }

    public function trusteeshipUrlToken(?string $trusteeshipUrlToken): self
    {
        $this->trusteeshipUrlToken = $this->nullableString($trusteeshipUrlToken);

        return $this;
    }

    public function trusteeshipUserIdType(?string $trusteeshipUserIdType): self
    {
        $this->trusteeshipUserIdType = $this->nullableString($trusteeshipUserIdType);

        return $this;
    }

    /** @param array<string, string> $trusteeshipUrls */
    public function trusteeshipUrls(array $trusteeshipUrls): self
    {
        $this->trusteeshipUrls = $trusteeshipUrls;

        return $this;
    }

    /** @param array<string, mixed> $trusteeshipCacheConfig */
    public function trusteeshipCacheConfig(array $trusteeshipCacheConfig): self
    {
        $this->trusteeshipCacheConfig = $trusteeshipCacheConfig;

        return $this;
    }

    public function resourceRegion(?string $resourceRegion): self
    {
        $this->resourceRegion = $this->nullableString($resourceRegion);

        return $this;
    }

    public function build(): ExternalInstancesCreateRequest
    {
        $this->validate();

        $request = new ExternalInstancesCreateRequest(
            $this->approvalCode,
            $this->status->value,
            $this->instanceId,
            ExternalInstanceLinkBuilder::make((string) $this->pcLink, $this->mobileLink),
            (string) $this->startTime,
            (string) $this->endTime,
            (string) $this->updateTime,
            $this->i18nResources
        );

        if ($this->userId !== null) {
            $request->setUserId($this->userId);
        }
        if ($this->openId !== null) {
            $request->setOpenId($this->openId);
        }
        if ($this->title !== null) {
            $request->setTitle($this->title);
        }
        if ($this->userName !== null) {
            $request->setUserName($this->userName);
        }
        if ($this->departmentId !== null) {
            $request->setDepartmentId($this->departmentId);
        }
        if ($this->departmentName !== null) {
            $request->setDepartmentName($this->departmentName);
        }

        $request
            ->setDisplayMethod($this->displayMethod->value)
            ->setUpdateMode($this->updateMode->value);

        if ($this->extra !== null) {
            $request->setExtra($this->extra);
        }
        if ($this->form !== null) {
            $request->setForm($this->form);
        }
        if ($this->taskList !== null) {
            $request->setTaskList($this->taskList);
        }
        if ($this->ccList !== null) {
            $request->setCcList($this->ccList);
        }
        if ($this->trusteeshipUrlToken !== null) {
            $request->setTrusteeshipUrlToken($this->trusteeshipUrlToken);
        }
        if ($this->trusteeshipUserIdType !== null) {
            $request->setTrusteeshipUserIdType($this->trusteeshipUserIdType);
        }
        if ($this->trusteeshipUrls !== null) {
            $request->setTrusteeshipUrls($this->trusteeshipUrls);
        }
        if ($this->trusteeshipCacheConfig !== null) {
            $request->setTrusteeshipCacheConfig($this->trusteeshipCacheConfig);
        }
        if ($this->resourceRegion !== null) {
            $request->setResourceRegion($this->resourceRegion);
        }

        return $request;
    }

    private function validate(): void
    {
        if ($this->approvalCode === null || $this->approvalCode === '') {
            throw new InvalidArgumentException('approvalCode 不能为空');
        }
        if ($this->status === null) {
            throw new InvalidArgumentException('status 不能为空');
        }
        if ($this->instanceId === null || $this->instanceId === '') {
            throw new InvalidArgumentException('instanceId 不能为空');
        }

        $pcLink = (string) ($this->pcLink ?? '');
        $mobileLink = (string) ($this->mobileLink ?? '');
        if ($pcLink === '' && $mobileLink === '') {
            throw new InvalidArgumentException('pcLink 与 mobileLink 至少需要传一个');
        }

        if ($this->startTime === null || $this->startTime === '') {
            throw new InvalidArgumentException('startTime 不能为空');
        }
        if ($this->updateTime === null || $this->updateTime === '') {
            throw new InvalidArgumentException('updateTime 不能为空');
        }
        if ($this->i18nResources === []) {
            throw new InvalidArgumentException('i18nResources 不能为空');
        }
        if ($this->userId === null && $this->openId === null && $this->userName === null) {
            throw new InvalidArgumentException('userId 和 openId 和 userName 至少需要传一个');
        }

        $requiredI18nKeys = [];
        if ($this->title !== null) {
            $requiredI18nKeys[] = $this->title;
        }
        if ($this->userName !== null) {
            $requiredI18nKeys[] = $this->userName;
        }
        if ($this->departmentName !== null) {
            $requiredI18nKeys[] = $this->departmentName;
        }
        if ($requiredI18nKeys !== []) {
            ExternalInstanceI18nBuilder::assertContainsKeys($this->i18nResources, $requiredI18nKeys);
        }
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
