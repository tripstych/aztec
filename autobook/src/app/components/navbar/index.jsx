import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import Logo from "../logo";
import NavItems from "./navItems";
import useScroll from "../../hooks/useScroll";

const NavBarContainer = styled.div`
  min-height: 68px;
  background-color: ${({ $isScrolled }) =>
    $isScrolled ? "rgba(0, 0, 0, 0.75)" : "#1d2124"};
  transition: background-color 0.3s ease-in-out, position 0.1s ease-in-out;
  ${tw`
    w-full
    flex
    flex-row
    items-center
    py-6
    px-6
    lg:px-20
    justify-between
  `}
  position: ${({ $isScrolled }) => ($isScrolled ? "fixed" : "absolute")};
  top: 0;
  left: 0;
  right: 0;
  z-index: 50;
  box-sizing: border-box;
`;

const LogoContainer = styled.div`
  ${tw`
    flex
    items-center
  `}
`;

const NavBar = () => {
  const isScrolled = useScroll(5);

  return;
  return (
    <NavBarContainer $isScrolled={isScrolled}>
      <LogoContainer>
        <Logo />
      </LogoContainer>
      <NavItems />
    </NavBarContainer>
  );
};

export default NavBar;
