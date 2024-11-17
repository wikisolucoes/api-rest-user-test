<template>
  <b-container fluid>
    <b-card header-tag="header" header-bg-variant="dark" header-text-variant="white" header-border-variant="dark"
      border-variant="dark" class="user-form">
      <template #header>
        <b-row>
          <b-col>
            <h4 class="mb-0">{{ cardTitle }}</h4>
          </b-col>
          <b-col class="text-end">
            <b-button variant="primary" size="sm" title="Salvar" @click="handleSubmit()" class="me-2">
              <i class="fa-solid fa-check"></i>
            </b-button>
            <b-button variant="danger" size="sm" title="Limpar formulário" @click="clearForm()" class="me-2">
              <i class="fa-solid fa-eraser"></i>
            </b-button>
            <b-button variant="light" size="sm" title="Voltar" @click="$router.push({ name: 'Home' })">
              <i class="fa-solid fa-reply"></i>
            </b-button>
          </b-col>
        </b-row>
      </template>
      <b-overlay :show="loading" rounded="sm">
        <b-form @submit.prevent="handleSubmit">
          <b-row>
            <b-col>
              <b-form-group label="Nome" :invalid="errors.name">
                <b-form-input size="sm" v-model="form.name" type="text" :state="!errors.name"
                  placeholder="Digite seu nome"></b-form-input>
                <b-form-invalid-feedback v-if="errors.name">{{ errors.name }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="Email" :invalid="errors.email">
                <b-form-input size="sm" v-model="form.email" type="email" :state="!errors.email"
                  placeholder="Digite seu email"></b-form-input>
                <b-form-invalid-feedback v-if="errors.email">{{ errors.email }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Tipo de Documento" :invalid="errors.document_type">
                <b-form-select size="sm" v-model="form.document_type" :options="documentTypes" @change="changeDocMask"
                  :state="!errors.document_type"></b-form-select>
                <b-form-invalid-feedback v-if="errors.document_type">{{ errors.document_type
                  }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="Documento" :invalid="errors.document">
                <b-form-input size="sm" v-model="form.document" type="text" :state="!errors.document"
                  v-mask="documentMask" placeholder="Digite o número do documento (CPF/CNPJ)"></b-form-input>
                <b-form-invalid-feedback v-if="errors.document">{{ errors.document }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <b-form-group label="Telefone" :invalid="errors.telephone">
                <b-form-input size="sm" v-model="form.telephone" type="text" :state="!errors.telephone"
                  v-mask="['(##) ####-####', '(##) #####-####']" placeholder="Digite seu telefone"></b-form-input>
                <b-form-invalid-feedback v-if="errors.telephone">{{ errors.telephone }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="Celular" :invalid="errors.cellphone">
                <b-form-input size="sm" v-model="form.cellphone" type="text" :state="!errors.cellphone"
                  v-mask="['(##) ####-####', '(##) #####-####']" placeholder="Digite seu celular"></b-form-input>
                <b-form-invalid-feedback v-if="errors.cellphone">{{ errors.cellphone }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <b-form-group label="Empresa" :invalid="errors.business">
                <b-form-input size="sm" v-model="form.business" type="text" :state="!errors.business"
                  placeholder="Digite o nome da empresa"></b-form-input>
                <b-form-invalid-feedback v-if="errors.business">{{ errors.business }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="Tipo de Usuário" :invalid="errors.id_user_type">
                <b-form-select size="sm" v-model="form.id_user_type" :options="userTypes"
                  :state="!errors.id_user_type"></b-form-select>
                <b-form-invalid-feedback v-if="errors.id_user_type">{{ errors.id_user_type }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <b-form-group label="Senha" :invalid="errors.password">
                <b-form-input size="sm" v-model="form.password" type="password" :state="!errors.password"
                  placeholder="Digite sua senha"></b-form-input>
                <b-form-invalid-feedback v-if="errors.password">{{ errors.password }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="Confirmar Senha" :invalid="errors.passwordConfirmation">
                <b-form-input size="sm" v-model="form.passwordConfirmation" type="password"
                  :state="!errors.passwordConfirmation" placeholder="Confirme sua senha"></b-form-input>
                <b-form-invalid-feedback v-if="errors.passwordConfirmation">{{ errors.passwordConfirmation
                  }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <b-form-group label="Status" :invalid="errors.status">
                <b-form-select size="sm" v-model="form.status" :options="statusOptions"
                  :state="!errors.status"></b-form-select>
                <b-form-invalid-feedback v-if="errors.status">{{ errors.status }}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col></b-col>
          </b-row>
        </b-form>
      </b-overlay>
    </b-card>
  </b-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        document_type: 'cpf',
        document: '',
        telephone: '',
        cellphone: '',
        business: '',
        status: 'A', // A = Ativo, I = Inativo
        password: '',
        passwordConfirmation: '',
        id_user_type: '', // Tipo de usuário
      },
      errors: {},
      documentMask: '###.###.###-##',
      documentTypes: [
        { value: 'cpf', text: 'CPF' },
        { value: 'cnpj', text: 'CNPJ' },
      ],
      statusOptions: [
        { value: 'A', text: 'Ativo' },
        { value: 'I', text: 'Inativo' },
      ],
      userTypes: [],
      cardTitle: 'Cadastrar Usuário',
      loading: false,
      userId: 0
    };
  },
  methods: {
    clearForm() {
      this.form.name = '';
      this.form.email = '';
      this.form.document_type = 'cpf';
      this.form.document = '';
      this.form.telephone = '';
      this.form.cellphone = '';
      this.form.business = '';
      this.form.status = 'A';
      this.form.password = '';
      this.form.passwordConfirmation = '';
      this.form.id_user_type = '';
    },
    changeDocMask() {
      this.$nextTick(() => {
        this.documentMask =
          this.form.document_type === 'cpf'
            ? '###.###.###-##'
            : '##.###.###/####-##';
      });
    },
    loadUser(userId) {
      this.loading = true;
      axios.get(`/api/users/${userId}`)
        .then(response => {
          if (response.status === 200) {
            this.form = { ...this.form, ...response.data.data };
            this.form.id_user_type = response.data.data.user_type.id;
          } else {
            console.error('Erro ao carregar usuário:', response.statusText);
            alert('Erro ao carregar usuário');
          }
          this.loading = false;
        })
        .catch(error => {
          this.handleError(error);
          this.loading = false;
        });
    },

    loadUserTypes() {
      this.loading = true;
      axios.get('/api/user-types')
        .then(response => {
          if (response.status === 200) {
            this.userTypes = response.data.data.map(type => ({
              value: type.id,
              text: type.name,
            }));
          } else {
            console.error('Erro ao carregar tipos de usuário:', response.statusText);
            alert('Erro ao carregar tipos de usuário');
          }
          this.loading = false;
        })
        .catch(error => {
          this.handleError(error);
          this.loading = false;
        });
    },
    validate() {
      this.errors = {};

      if (!this.form.name) {
        this.errors.name = 'Nome é obrigatório';
      }

      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      if (!this.form.email || !emailPattern.test(this.form.email)) {
        this.errors.email = 'Email inválido';
      }

      if (!this.form.document) {
        this.errors.document = 'Documento é obrigatório';
      }

      if (!this.form.telephone) {
        this.errors.telephone = 'Telefone é obrigatório';
      }

      if (!this.form.cellphone) {
        this.errors.cellphone = 'Celular é obrigatório';
      }

      if (!this.form.business) {
        this.errors.business = 'Nome da empresa é obrigatório';
      }

      if (!['A', 'I'].includes(this.form.status)) {
        this.errors.status = 'Status deve ser válido';
      }

      if (!this.form.password) {
        this.errors.password = 'Senha é obrigatória';
      } else if (this.form.password.length < 8) {
        this.errors.password = 'A senha deve ter pelo menos 8 caracteres';
      }

      if (!this.form.passwordConfirmation) {
        this.errors.passwordConfirmation = 'Confirmação de senha é obrigatória';
      } else if (this.form.password !== this.form.passwordConfirmation) {
        this.errors.passwordConfirmation = 'As senhas não correspondem';
      }

      if (!this.form.id_user_type) {
        this.errors.id_user_type = 'Tipo de usuário é obrigatório';
      }

      if (!this.form.document_type) {
        this.errors.document_type = 'Tipo de documento é obrigatório';
      }

      return Object.keys(this.errors).length === 0; // Se não houver erros, retorna true
    },

    handleError(error) {
      if (error.response) {
        if (error.response.data.errors) {
          this.errors = error.response.data.errors;
        } else {
          alert("Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.");
        }
      } else {
        alert("Erro de conexão. Verifique sua conexão com a internet.");
      }
      this.loading = false;
    },

    async handleSubmit() {
      this.errors = {}; // Limpa os erros antes de enviar

      if (this.validate()) {
        try {
          this.loading = true;
          const endpoint = this.userId ? `/api/users/${this.userId}` : '/api/users';
          const method = this.userId ? 'put' : 'post';
          const response = await axios({ method, url: endpoint, data: this.form });

          if ([200, 201].includes(response.status)) {
            alert('Usuário salvo com sucesso!');
            this.$router.push({ name: 'Home' });
          } else {
            alert('Erro ao salvar usuário.');
          }
        } catch (error) {
          this.handleError(error);
        } finally {
          this.loading = false;
        }
      }
    },
  },
  async mounted() {
    this.loadUserTypes();

    this.userId = Number(this.$route.params.id);
    if (this.userId) {
      await this.loadUser(this.userId);
      this.cardTitle = 'Editar Usuário';
    } else {
      this.cardTitle = 'Cadastrar Usuário';
    }
  },
};
</script>

<style lang="scss" src="../../styles/pages/user/user-form.scss"></style>