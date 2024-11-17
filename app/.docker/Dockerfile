# Etapa 1: Construção do Projeto
FROM node:18-alpine

# Diretório de trabalho no container
WORKDIR /app

# Copiar apenas os arquivos necessários
COPY package*.json ./

# Instalar as dependências
RUN npm install

# Copiar o restante do código para o container
COPY . .

# Expor a porta usada pelo Vite
EXPOSE 5173

# Comando para iniciar o Vite no modo desenvolvimento
CMD ["npm", "run", "dev", "--", "--host"]
