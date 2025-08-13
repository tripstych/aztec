import React, { useState } from "react";
import styled, { css } from "styled-components";
import tw from "twin.macro";
import { slide as Menu } from "react-burger-menu";
import { useMediaQuery } from "react-responsive";
import { SCREENS } from "../responsive";
import { menuStyles } from "./menuStyles";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown } from "@fortawesome/free-solid-svg-icons";
import { NavLink } from "react-router-dom";

const ListContainer = styled.ul`
  z-index: 999;
  ${tw`
    flex
    list-none
  `}
`;

const NavItem = styled(({ menu, ...rest }) => <li {...rest} />)`
  ${tw`
    text-sm
    md:text-base
    text-white
    font-bold
    mr-1
    md:mr-5
    cursor-pointer
    transition
    duration-200
    ease-in-out
    relative
  `}

  & > a:hover {
    ${tw`
      text-gray-300
    `}
  }

  a.active {
    ${tw`
      text-cBlue
    `}
  }

  ${({ menu }) =>
    menu &&
    css`
      ${tw`
        text-white
        text-xl
        mb-3
      `}
    `}
`;

const IconWrapper = styled.span`
  ${tw`
    ml-2
    text-white
    text-xs
  `}
`;

const DropdownMenu = styled(({ isOpen, ...rest }) => <ul {...rest} />)`
  z-index: 1000;
  background-color: #1d2124;
  ${tw`
    absolute
    top-full
    right-0
    mt-2
    w-40
    list-none
    text-white
    rounded-md
    shadow-lg
    p-2
    opacity-0
    invisible
    transition-all
    duration-200
    border-b-2
    border-cBlue
  `}

  ${({ isOpen }) =>
    isOpen &&
    css`
      ${tw`
        opacity-100
        visible
      `}
    `}
`;

const DropdownItem = styled.li`
  ${tw`
    text-sm
    font-medium
    p-2
    rounded-md
    hover:text-gray-500
  `}
`;

const NavItems = () => {
  const isMobile = useMediaQuery({ maxWidth: SCREENS.sm });
  const [isDropdownOpen, setDropdownOpen] = useState(false);

  const toggleDropdown = () => setDropdownOpen((prev) => !prev);

    return;

  return (
    <ListContainer>
      <NavItem>
        <NavLink to="/" end>
          Home
        </NavLink>
      </NavItem>
      <NavItem>
        <NavLink to="/quote">Free Quote</NavLink>
      </NavItem>
      <NavItem>
        <NavLink to="/mobile-service">Mobile Service</NavLink>
      </NavItem>
      <NavItem>
        <NavLink to="/windshield-replacement">Windshield Replacement</NavLink>
      </NavItem>
      <NavItem onClick={toggleDropdown}>
        More
        <IconWrapper>
          <FontAwesomeIcon icon={faChevronDown} />
        </IconWrapper>
        <DropdownMenu isOpen={isDropdownOpen} onMouseLeave={toggleDropdown}>
          <DropdownItem>
            <NavLink to="/rock-chip-repair">Rock Chip Repair</NavLink>
          </DropdownItem>
          <DropdownItem>
            <NavLink to="/privacy-policy">Privacy Policy</NavLink>
          </DropdownItem>
          <DropdownItem>
            <NavLink to="/contact">Contact</NavLink>
          </DropdownItem>
        </DropdownMenu>
      </NavItem>
    </ListContainer>
  );
};

export default NavItems;
