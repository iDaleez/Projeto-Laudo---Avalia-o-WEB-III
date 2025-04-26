<?php

class Laudo
{
    private ?int $id;
    private string $paciente;
    private string $proprietario;
    private string $animal;
    private string $descricao;
    private string $diagnostico;
    private string $data;

    public function __construct(
        ?int $id,
        string $paciente,
        string $proprietario,
        string $animal,
        string $descricao,
        string $diagnostico,
        string $data
    ) {
        $this->id = $id;
        $this->paciente = $paciente;
        $this->proprietario = $proprietario;
        $this->animal = $animal;
        $this->descricao = $descricao;
        $this->diagnostico = $diagnostico;
        $this->data = $data;
    }

    public function getId(): ?int { return $this->id; }
    public function getPaciente(): string { return $this->paciente; }
    public function getProprietario(): string { return $this->proprietario; }
    public function getAnimal(): string { return $this->animal; }
    public function getDescricao(): string { return $this->descricao; }
    public function getDiagnostico(): string { return $this->diagnostico; }
    public function getData(): string { return $this->data; }

    public function setPaciente(string $paciente): void { $this->paciente = $paciente; }
    public function setProprietario(string $proprietario): void { $this->proprietario = $proprietario; }
    public function setAnimal(string $animal): void { $this->animal = $animal; }
    public function setDescricao(string $descricao): void { $this->descricao = $descricao; }
    public function setDiagnostico(string $diagnostico): void { $this->diagnostico = $diagnostico; }
    public function setData(string $data): void { $this->data = $data; }
}