import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [
        'club',
        'teamName',
        'shooter',
        'firstName',
        'lastName',
        'competitionTeam',
        'useClubButton',
        'sharedWeaponCode',
        'sharedWeaponsCode',
        'sharedWeapons',
        'sharedWeaponsList',
        'sharedWeaponsToggle',
    ]

    expandedSharedWeapons = false

    connect() {
        this.toggleShooterNameFields();
        this.toggleTeamNameFields();
        this.syncSharedWeaponCode();
        this.filterSharedWeapons();
        this.updateSharedWeaponsCollapse();
    }

    toggleShooterNameFields() {
        const selectedShooter = this.shooterTarget.querySelector('input[type="radio"]:checked');
        const hasSelectedShooter = selectedShooter !== null && selectedShooter.value.trim() !== '';

        this.firstNameTarget.disabled = hasSelectedShooter;
        this.lastNameTarget.disabled = hasSelectedShooter;
    }

    toggleTeamNameFields() {
        if (!this.hasCompetitionTeamTarget || !this.hasTeamNameTarget || !this.hasUseClubButtonTarget) {
            return;
        }

        const selectedTeam = this.competitionTeamTarget.querySelector('input[type="radio"]:checked');
        const hasSelectedTeam = selectedTeam !== null && selectedTeam.value.trim() !== '';

        this.teamNameTarget.disabled = hasSelectedTeam;
        this.useClubButtonTarget.disabled = hasSelectedTeam;
    }

    syncSharedWeaponCode() {
        this.sharedWeaponsCodeTarget.value = this.sharedWeaponCodeTarget.value;
        this.filterSharedWeapons();
    }

    filterSharedWeapons() {
        const selectedCode = this.sharedWeaponsCodeTarget.value.trim();

        this.sharedWeaponsTargets.forEach((sharedWeapon) => {
            sharedWeapon.hidden = selectedCode !== '' && sharedWeapon.dataset.sharedWeaponCode !== selectedCode;
            this.expandedSharedWeapons = false;
        });

        this.updateSharedWeaponsCollapse();
    }

    toggleSharedWeapons() {
        this.expandedSharedWeapons = !this.expandedSharedWeapons;
        this.updateSharedWeaponsCollapse();
    }

    updateSharedWeaponsCollapse() {
        if (!this.hasSharedWeaponsListTarget || !this.hasSharedWeaponsToggleTarget) {
            return;
        }

        console.log(this.expandedSharedWeapons)

        if (this.expandedSharedWeapons === false) {
            this.sharedWeaponsListTarget.style.maxHeight = '2rem'
            this.sharedWeaponsListTarget.style.overflow = 'hidden'
        } else {
            this.sharedWeaponsListTarget.style.maxHeight = ''
            this.sharedWeaponsListTarget.style.overflow = ''
        }

        const isOverflowing = this.sharedWeaponsListTarget.scrollHeight > this.sharedWeaponsListTarget.clientHeight

        if (isOverflowing) {
            this.sharedWeaponsToggleTarget.hidden = false
            this.sharedWeaponsToggleTarget.textContent = 'Zobraziť všetky'

            return;
        }

        if (isOverflowing === false && this.sharedWeaponsListTarget.style.overflow === '') {
            this.sharedWeaponsToggleTarget.hidden = false
            this.sharedWeaponsToggleTarget.textContent = 'Skryť'

            return;
        }

        this.sharedWeaponsToggleTarget.hidden = true
    }

    selectSharedWeapon(event) {
        const code = event.currentTarget.dataset.sharedWeaponCode ?? '';

        this.sharedWeaponCodeTarget.value = code;
        this.sharedWeaponCodeTarget.dispatchEvent(new Event('input', { bubbles: true }));
        this.sharedWeaponCodeTarget.dispatchEvent(new Event('change', { bubbles: true }));
        this.syncSharedWeaponCode();
    }

    setTeamFromClub(event) {
        event.preventDefault();
        this.teamNameTarget.value = this.clubTarget.value;
        this.teamNameTarget.dispatchEvent(new Event('input', { bubbles: true }));
        this.teamNameTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }
}
