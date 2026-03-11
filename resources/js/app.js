// resources/js/app.js

// Registramos el componente 'selectMultiple' en Alpine
document.addEventListener('alpine:init', () => {
    
    // Nombramos el componente 'selectMultiple' y le decimos que recibirá 2 parámetros: selectedData y options
    Alpine.data('selectMultiple', (selectedData, options) => ({
        open: false,
        search: '',
        selected: selectedData, // Recibido por @entangle
        options: options,       // Opciones pasadas por PHP
        
        get filteredOptions() {
            if (this.search === '') return this.options;
            return this.options.filter(opt => opt.label.toString().toLowerCase().includes(this.search.toLowerCase()));
        },

        toggle(value) {
            if (!Array.isArray(this.selected)) this.selected = [];
            
            // Si tú necesitas esa validación específica (super_admin) aquí, aunque idealmente iría en Livewire:
            if (value === 'super_admin' && !this.selected.includes('super_admin')) {
                this.$wire.tryAddRole('super_admin');
                return;
            }
            
            if (this.selected.includes(value)) {
                this.selected = this.selected.filter(i => i !== value);
            } else {
                this.selected.push(value);
            }
            this.$refs.searchInput.focus();
        },

        remove(value) {
            if (!Array.isArray(this.selected)) this.selected = [];
            this.selected = this.selected.filter(i => i !== value);
        },

        getLabel(value) {
            let opt = this.options.find(o => o.value == value);
            return opt ? opt.label : value;
        }
    }));
});