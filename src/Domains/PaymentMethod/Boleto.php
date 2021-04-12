<?php

namespace Braspag\Split\Domains\PaymentMethod;

use DateTime;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Validation\Validator as v;

class Boleto implements BraspagSplit
{
    /** @var string Payment.Bank */
    private $bank;

    /** @var string Payment.BoletoNumber */
    private $boletoNumber;

    /** @var string Payment.Assignor */
    private $assignor;

    /** @var string Payment.Demonstrative */
    private $demonstrative;

    /** @var DateTime Payment.ExpirationDate */
    private $expirationDate;

    /** @var string Payment.Identification */
    private $identification;

    /** @var string Payment.NullifyDays */
    private $nullifyDays;

    /** @var string Payment.Instructions */
    private $instructions;

    /** @var int Payment.DaysToFine */
    private $daysToFine;

    /** @var float Payment.FineRate */
    private $fineRate;

    /** @var int Payment.FineAmount */
    private $fineAmount;

    /** @var int Payment.DaysToInterest */
    private $daysToInterest;

    /** @var float Payment.InterestRate */
    private $interestRate;

    /** @var int Payment.InterestAmount */
    private $interestAmount;


    /** @var string Payment.Url */
    private $url;

    /** @var string Payment.BarCodeNumber */
    private $barCodeNumber;

    /** @var string Payment.DigitableLine */
    private $digitableLine;

    /** @var string Payment.Address */
    private $address;

    /** @var int Payment.Status */
    private $status;

    /**
     * Nome do banco
     *
     * @param string $value
     */
    public function setBank(string $value)
    {
        $this->bank = $value;
    }

    /**
     * @return string|null
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Número do Boleto (“Nosso Número”). Caso preenchido, sobrepõe o valor configurado no meio de pagamento.
     * A regra varia de acordo com o Provider utilizado
     *
     * @param string $value
     *
     * @return self
     */
    public function setBoletoNumber(string $value): self
    {
        $this->boletoNumber = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBoletoNumber(): ?string
    {
        return $this->boletoNumber;
    }

    /**
     * Nome do Cedente. Caso preenchido, sobrepõe o valor configurado no meio de pagamento
     *
     * @param string $value
     *
     * @return self
     */
    public function setAssignor(string $value): self
    {
        $this->assignor = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAssignor(): ?string
    {
        return $this->assignor;
    }

    /**
     * Texto de Demonstrativo. Caso preenchido, sobrepõe o valor configurado no meio de pagamento.
     *
     * @param string $value
     *
     * @return self
     */
    public function setDemonstrative(string $value): self
    {
        $this->demonstrative = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDemonstrative(): ?string
    {
        return $this->demonstrative;
    }

    /**
     * Dias para vencer o boleto. Caso não esteja previamente cadastrado no meio de pagamento, o envio deste campo
     * é obrigatório. Se enviado na requisição, sobrepõe o valor configurado no meio de pagamento.
     *
     * @param DateTime $value
     *
     * @throws \InvalidArgumentException Se a data for inferior à atual
     *
     * @return self
     */
    public function setExpirationDate(DateTime $value): self
    {
        if (new DateTime() > $value) {
            throw new \InvalidArgumentException('Expiration date cannot be less than the current date', 6000);
        }

        $this->expirationDate = $value;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getExpirationDate(): ?DateTime
    {
        return $this->expirationDate;
    }

    /**
     * CNPJ do Cedente. Caso preenchido, sobrepõe o valor configurado no meio de pagamento
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException Se o CNPJ for inválido
     *
     * @return self
     */
    public function setIdentification(string $value): self
    {
        if (!v::cnpj()->validator($value)) {
            throw new \InvalidArgumentException("CNPJ $value is invalid", 2001);
        }

        $this->identification = preg_replace('/\D/', '', $value);
        return $this;
    }

    /**
     * @return string|null Somente número
     */
    public function getIdentification(): ?string
    {
        return $this->identification;
    }

    /**
     * Prazo para baixa automática do boleto
     *
     * O cancelamento automático do boleto acontecerá após o número de dias estabelecido neste campo contado a
     * partir da data do vencimento. Ex.: um boleto com vencimento para 15/12 que tenha em seu registro o prazo
     * para baixa de 5 dias, poderá ser pago até 20/12, após esta data o título é cancelado
     *
     * @param int $value
     *
     * @return self
     */
    public function setNullifyDays(int $value): self
    {
        $this->nullifyDays = $value;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNullifyDays(): ?int
    {
        return $this->nullifyDays;
    }

    /**
     * Instruções do Boleto. Caso preenchido, sobrepõe o valor configurado no meio de pagamento.
     *
     * @param string $value
     *
     * @return self
     */
    public function setInstructions(string $value): self
    {
        $this->instructions = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    /**
     * Quantidade de dias após o vencimento para cobrar o valor da multa, em número inteiro. Ex: 3
     */
    public function setDaysToFine(int $value): self
    {
        $this->daysToFine = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDaysToFine(): ?int
    {
        return $this->daysToFine;
    }

    /**
     * Valor da multa após o vencimento em percentual, com base no valor do boleto (%).
     * Não enviar se utilizar FineAmount. Ex: 10.12345 = 10.12345%
     *
     * @param float $value
     *
     * @return self
     */
    public function setFineRate(float $value): self
    {
        $this->fineRate = $value;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFineRate(): ?float
    {
        return $this->fineRate;
    }

    /**
     * Valor da multa após o vencimento em valor absoluto em centavos. Não enviar se utilizar FineRate.
     * Ex: 1000 = R$ 10,00
     *
     * @param int $value
     *
     * @return self
     */
    public function setFineAmount(int $value): self
    {
        $this->fineAmount = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFineAmount(): ?int
    {
        return $this->fineAmount;
    }

    /**
     * Quantidade de dias após o vencimento para iniciar a cobrança de juros por dia sobre o valor do boleto,
     * em número inteiro. Ex: 3
     *
     * @param int $value
     *
     * @return self
     */
    public function setDaysToInterest(int $value): self
    {
        $this->daysToInterest = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDaysToInterest(): ?int
    {
        return $this->daysToInterest;
    }

    /**
     * Valor de juros mensal após o vencimento em percentual, com base no valor do boleto (%).
     * O valor de juros é cobrado proporcionalmente por dia (Mensal dividido por 30).
     * Permitido decimal com até 5 casas decimais. Não enviar se utilizar InterestAmount. Ex: 10.12345
     *
     * @param float $value
     *
     * @return self
     */
    public function setInterestRate(float $value): self
    {
        $this->interestRate = $value;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getInterestRate(): ?float
    {
        return $this->interestRate;
    }

    /**
     * Valor absoluto de juros diário após o vencimento em centavos.
     * Não enviar se utilizar InterestRate. Ex: 1000 = R$ 10,00
     *
     * @param int $value
     *
     * @return self
     */
    public function setInterestAmount(int $value): self
    {
        $this->interestAmount = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInterestAmount(): ?int
    {
        return $this->interestAmount;
    }

    /**
     * @return string|null URL do Boleto gerado
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return string|null Representação numérica do código de barras
     */
    public function getBarCodeNumber(): ?string
    {
        return $this->barCodeNumber;
    }

    /**
     * @return string|null Linha digitável
     */
    public function getDigitableLine(): ?string
    {
        return $this->digitableLine;
    }

    /**
     * @return string|null Endereço do Loja cadastrada no banco
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int|null Status da Transação
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->boletoNumber = $value->BoletoNumber ?? null;
        $this->assignor = $value->Assignor ?? null;
        $this->demonstrative = $value->Demonstrative ?? null;
        $this->identification = $value->Identification ?? null;
        $this->instructions = $value->Instructions ?? null;
        $this->nullifyDays = $value->NullifyDays ?? null;
        $this->daysToFine = $value->DaysToFine ?? null;
        $this->fineRate = $value->FineRate ?? null;
        $this->fineAmount = $value->FineAmount ?? null;
        $this->daysToInterest = $value->DaysToInterest ?? null;
        $this->interestRate = $value->InterestRate ?? null;
        $this->interestAmount = $value->InterestAmount ?? null;
        $this->url = $value->Url ?? null;
        $this->barCodeNumber = $value->BarCodeNumber ?? null;
        $this->digitableLine = $value->DigitableLine ?? null;
        $this->address = $value->Address ?? null;
        $this->status = $value->Status ?? null;
        $this->bank = $value->Bank ?? null;

        if (isset($value->ExpirationDate)) {
            $this->expirationDate = DateTime::createFromFormat('Y-m-d', $value->ExpirationDate);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);
        $arr = array_combine($keys, $values);

        if ($this->expirationDate) {
            $arr['ExpirationDate'] = $this->expirationDate->format('Y-m-d');
        }

        return array_filter($arr, function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
