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
    ]

    connect() {
        this.toggleShooterNameFields();
        this.toggleTeamNameFields();
        this.syncSharedWeaponCode();
        this.filterSharedWeapons();
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
        });
    }

    setTeamFromClub(event) {
        event.preventDefault();
        this.teamNameTarget.value = this.clubTarget.value;
        this.teamNameTarget.dispatchEvent(new Event('input', { bubbles: true }));
        this.teamNameTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }
}
