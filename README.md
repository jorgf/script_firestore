# Firestore data management
## Objetivo
O projeto, inicialmente, está sendo usado para duas principais atividades:
- Importar dados do ambiente local para o firestore e
- Exportar dados do firestore para um ambiente local

## Requisitos
- gRPC para PHP (https://cloud.google.com/php/grpc)

## Processos
Após instalar e ativar gRPC para PHP, é necessário baixar a chave privada do projeto, copiar e colar as informações no arquivo 'settings.php' dentro da pasta 'config'.

Para gerar a chave privada acesse sua conta no firestore e siga o caminho abaixo:

    Firestore -> visão geral do projeto -> configurações do projeto -> Contas de serviço -> gerar nova chave privada.

Será feito um download de um arquivo '.json' com toda a configuração necessária para acessar o projeto no firestore 
<strong>LEMBRE-SE QUE ESSA CHAVE NÃO DEVE SER PUBLICADA</strong>

## Executando o script
1. clone o diretório para seu ambiente local
     
        git clone <link do dir>
     
2. No diretorio do projeto execute o comando
    
       php index.php <nome_action> <nome_collection> <nome_diretorio> 

    onde:

    <nome_action> pode receber:
    - 'importFS' para importar os dados do ambiente local para o firestore;
    - 'exportFS' para exportar os dados do firestore para o ambiente local;
    - 'deletDocument' para deletar um documento 
    <nome_collection> recebe o nome de uma coleção específica para importar ou exportar, caso queira exportar/importar todas as colecções, basta usar o termo 'all'.

    <nome_diretorio> recebe o nome que será dado ao diretório principal para exportar/importar os dados
---
### Observação importante

Os dados serão organizados da seguinte forma:
- Coleções serão diretórios (ou subdiretórios no caso de subcoleções);
- Documentos serão os arquivos '.json' e os campos dos documentos ficarão armazenados nos respectivos arquivos.
---
## Referências:

https://firebase.google.com/docs/firestore/quickstart

https://www.npmjs.com/package/firestore-export-import?activeTab=explore