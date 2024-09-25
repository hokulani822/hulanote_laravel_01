<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&family=Dancing+Script:wght@700&display=swap');
    
    .account-deletion-section {
        margin-top: 2rem;
        padding: 2rem;
        background-color: rgba(230, 230, 250, 0.85); /* パープルの背景 */
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .account-deletion-section h2 {
        font-family: 'Dancing Script', cursive;
        font-size: 2.5rem;
        color: #8B7355;
        text-align: center;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(139,115,85,0.1);
    }
    .btn-danger {
        padding: 0.75rem 1.5rem;
        background-color: #D2B48C;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-danger:hover {
        background-color: #8B4513;
    }
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: white;
        padding: 2rem;
        border-radius: 10px;
        max-width: 500px;
        width: 100%;
    }
    .modal-content h2 {
        font-size: 1.5rem;
        color: #8B7355;
        margin-bottom: 1rem;
    }
    .modal-content input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #D2B48C;
        border-radius: 5px;
        font-size: 1rem;
        margin-top: 0.5rem;
    }
    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }
    .btn-secondary {
        padding: 0.75rem 1.5rem;
        background-color: #6C757D;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-right: 0.5rem;
    }
    .btn-secondary:hover {
        background-color: #5A6268;
    }
    .text-sm {
        font-size: 0.875rem;
    }
    .text-gray-600 {
        color: #718096;
    }
    .mt-1 {
        margin-top: 0.25rem;
    }
    .mt-4 {
        margin-top: 1rem;
    }
    .mt-6 {
        margin-top: 1.5rem;
    }
</style>

<div class="account-deletion-section">
    <h2>アカウントの削除</h2>
    <p class="text-sm text-gray-600 mt-1">
        アカウントが削除されると、すべてのリソースとデータが永久に削除されます。アカウントを削除する前に、保持したいデータや情報をダウンロードしてください。
    </p>

    <button class="btn-danger mt-4" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        アカウントを削除
    </button>

    <div x-data="{ show: false, focusables() { return [...$el.querySelectorAll('a, button, input, textarea, select, details, [tabindex]:not([tabindex=\'-1\'])')] }, firstFocusable() { return this.focusables()[0] }, lastFocusable() { return this.focusables().slice(-1)[0] }, nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() }, prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() }, nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) }, prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 }, autofocus() { let focusable = $el.querySelector('[autofocus]'); if (focusable) focusable.focus() } }" x-init="$watch('show', value => value && setTimeout(autofocus, 50))" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false" x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()" x-on:keydown.shift.tab.prevent="prevFocusable().focus()" x-show="show" class="modal-backdrop" style="display: none;">
        <div class="modal-content" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <h2>本当にアカウントを削除しますか？</h2>
            <p class="text-sm text-gray-600 mt-1">
                アカウントが削除されると、すべてのリソースとデータが永久に削除されます。アカウントを完全に削除することを確認するために、パスワードを入力してください。
            </p>
            <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                @csrf
                @method('delete')
                <div>
                    <label for="password" class="sr-only">パスワード</label>
                    <input id="password" name="password" type="password" placeholder="パスワード" required>
                    @error('password', 'userDeletion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn-secondary" x-on:click="show = false">
                        キャンセル
                    </button>
                    <button type="submit" class="btn-danger">
                        アカウントを削除
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>