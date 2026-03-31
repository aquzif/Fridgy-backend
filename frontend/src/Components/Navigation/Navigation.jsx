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
    background: linear-gradient(165deg, #121631 0%, #171d42 45%, #1e2755 100%);
    border-right: 1px solid rgba(255, 255, 255, 0.08);
    width: 280px;
    height: 100vh;
    transition: left 0.2s ease-in;
    z-index: 1000;
    box-shadow: 10px 0 30px rgba(12, 16, 40, 0.25);
    box-sizing: border-box;
    padding: 20px 16px 16px;


    @media (max-width: 768px) {
        width: 100vw;
        padding-top: 64px;
    }

`;

const Title = styled.h2`
    color: #f5f7ff;
    font-weight: bold;
    font-size: 34px;
    text-align: left;
    letter-spacing: 2px;
    height: 48px;
    margin-bottom: 10px;
    padding-left: 8px;

    @media (max-width: 768px) {
      text-align: center;
      padding: 0;
    }
`;

const MiniNav = styled.div`
    display: flex;
    justify-content: flex-start;
    flex-direction: row;
    gap: 10px;
    width: calc(100% - 16px);
    margin: 0 8px 18px;
    padding: 10px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(6px);


    @media (max-width: 768px) {
        justify-content: center;
    }
`;

const ExitIcon = styled(Icon)`
    position: absolute;
    top: 14px;
    left: 14px;
    width: 40px;
    height: 40px;
    color: #d6ddff;
    padding: 6px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.08);
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
            <nav>
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
