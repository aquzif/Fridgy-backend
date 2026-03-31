import styled from "styled-components";
import {useNavigate} from "react-router-dom";
import NavItem from "@/Components/NavItem/NavItem";

import basketOutline from '@iconify/icons-mdi/basket-outline';
import receiptText from '@iconify/icons-mdi/receipt-text';
import fruitWatermelon from '@iconify/icons-mdi/fruit-watermelon';
import MiniNavItem from "@/Components/MiniNavItem/MiniNavItem";
import accountIcon from '@iconify/icons-mdi/account';
import cogIcon from '@iconify/icons-mdi/cog';
import baselineMeetingRoom from '@iconify/icons-ic/baseline-meeting-room';
import chevronLeft from '@iconify/icons-mdi/chevron-left';
import burgerIcon from '@iconify/icons-mdi/burger';
import calendarIcon from '@iconify/icons-mdi/calendar';
import gearIcon from '@iconify/icons-mdi/gear';
import fridgeIcon from '@iconify/icons-mdi/fridge-outline';
import AuthAPI from "@/API/AuthAPI";
import store from "@/Store/store";
import {logout} from "@/Store/Reducers/AuthReducer";
import toast from "react-hot-toast";
import {useMediaQuery} from "@/Hooks/useMediaQuery";
import {Icon} from "@iconify/react/offline";


const Container = styled.div`
    background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
    width: 250px;
    height: 100vh;
    transition: left 0.2s ease-in;
    z-index: 1000;
    border-right: 1px solid rgba(148, 163, 184, 0.2);
    box-shadow: 10px 0 30px rgba(2, 6, 23, 0.35);
    display: flex;
    flex-direction: column;

    @media (max-width: 768px) {
        width: 100vw;
    }

`;

const Title = styled.h2`
    color: #e2e8f0;
    font-weight: 700;
    font-size: 30px;
    padding-top: 14px;
    text-align: center;
    letter-spacing: 1px;
    height: 58px;

    @media (max-width: 768px) {
    padding: 26px 0px 20px 0;
    }
`;

const MiniNav = styled.div`
    display: flex;
    justify-content: center;
    flex-direction: row;
    width: calc(100% - 28px);
    height: 44px;
    margin: 0 auto 12px auto;
    background: rgba(148, 163, 184, 0.15);
    border: 1px solid rgba(148, 163, 184, 0.22);
    border-radius: 14px;
    backdrop-filter: blur(6px);

    @media (max-width: 768px) {
        height: 50px;
    }
`;

const ExitIcon = styled(Icon)`
    position: absolute;
    top: 20px;
    left: 16px;
    width: 42px;
    height: 42px;
    color: #cbd5e1;
`;

const Navigation = ({
    open,
    onClose
}) => {

    const navigate = useNavigate();

    const onAvatarCLick = () => navigate('/profil');
    const onSettingsClick = () => navigate('/ustawienia');
    const onLogoutClick = () => {
        toast.promise(AuthAPI.logout(),{
            loading: 'Wylogowywanie...',
            success: 'Wylogowano pomyślnie',
            error: 'Wylogowano z błędami'
        }).finally(() => {
            store.dispatch(logout());
        });
    }

    const isNavMobile = useMediaQuery('(max-width: 768px)');

    return (
        <Container
            style={isNavMobile ? {
                left: open ? '0' : '-100vw',
                position: 'absolute',

            } : {}}
        >
            {isNavMobile && <ExitIcon icon={chevronLeft} onClick={onClose} />}
            <Title>Fridgy</Title>

            <MiniNav>
                <MiniNavItem icon={accountIcon} onClick={onAvatarCLick} />
                <MiniNavItem icon={cogIcon} onClick={onSettingsClick} />
                <MiniNavItem icon={baselineMeetingRoom} onClick={onLogoutClick} />
            </MiniNav>
            <nav style={{ paddingBottom: '16px', overflowY: 'auto' }}>
                <NavItem name={'Kalendarz'} icon={calendarIcon} url={'/kalendarz'} />
                <NavItem name={'Lista zakupów'} icon={basketOutline} url={'/lista-zakupow'} />
                <NavItem name={'Przepisy'} icon={receiptText} url={'/przepisy'} />
                <NavItem name={'Fast Food'} icon={burgerIcon} url={'/fast-food'} />
                <NavItem name={'Produkty'} icon={fruitWatermelon} url={'/produkty'} />
                <NavItem name={'Szafka'} icon={fridgeIcon} url={'/szafka'} />
                <NavItem name={'Ustawienia aplikacji'} icon={gearIcon} url={'/admin/ustawienia'} />

            </nav>
        </Container>
    )

}

export default Navigation;
