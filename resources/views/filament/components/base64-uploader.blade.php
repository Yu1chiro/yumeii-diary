<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{
        state: $wire.$entangle('{{ $getStatePath() }}'),
        previewUrl: null,
    
        init() {
            this.previewUrl = this.state;
        },
    
        handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;
    
            const reader = new FileReader();
    
            reader.onload = (e) => {
                // Simpan format Base64 murni ke dalam state Filament
                this.state = e.target.result;
                this.previewUrl = e.target.result;
            };
    
            reader.readAsDataURL(file);
        }
    }">
        <!-- Input File -->
        <input type="file" accept="image/*" x-on:change="handleFileChange"
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 transition-all cursor-pointer">

        <!-- Preview Gambar -->
        <div x-show="previewUrl" class="mt-4" style="display: none;">
            <p class="text-sm text-gray-500 mb-2">Preview:</p>
            <img :src="previewUrl" alt="Preview"
                class="h-32 w-auto rounded-xl shadow-sm object-cover border-2 border-pink-200">
        </div>
    </div>
</x-dynamic-component>
