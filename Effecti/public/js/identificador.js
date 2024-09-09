// Função para gerar um número aleatório
function gerarNumeroAleatorio() {
    return Math.floor(Math.random() * 1000000000); // Gera um número aleatório entre 0 e 1.000.000.000
}

// Função para converter uma string para um array de bytes
async function stringToBytes(str) {
    const encoder = new TextEncoder();
    return encoder.encode(str);
}
 
// Função para gerar o hash SHA-256
async function generateSHA256Hash(data) {
    const bytes = await stringToBytes(data);
    const hashBuffer = await crypto.subtle.digest('SHA-256', bytes);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    return hashHex;
}
 
// Função principal para gerar e criptografar um número aleatório
async function gerarNumeroEChave() {
    // Gera um número aleatório
    const numeroAleatorio = gerarNumeroAleatorio();

    // Converte o número aleatório para string
    const stringNumero = numeroAleatorio.toString();

    // Gera o hash SHA-256 da string do número
    const hash = await generateSHA256Hash(stringNumero);

    // Pega os primeiros 20 caracteres do hash
    const chaveUnica = hash.substring(0, 20);

    //console.log('Número Aleatório:', numeroAleatorio);
    //console.log('String Número:', stringNumero);
    //console.log('Hash SHA-256:', hash);
    //console.log('Chave Única:', chaveUnica);

    window.localStorage.setItem("key",hash);
}

// Executa a função
gerarNumeroEChave();