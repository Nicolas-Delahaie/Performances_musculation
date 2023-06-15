import Logo from '../img/assets/logo.jpg';

function Header() {
    return (
        <header id="header">
            <img src={Logo} alt="logo" />
            <h1>Peng Records</h1>
            <div className="zoneDroite">
                <a>User</a>
                <a>Profil</a>
                <a>Deconnexion</a>
            </div>
        </header>
    );
}
export default Header;