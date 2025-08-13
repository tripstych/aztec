import React from "react";
import { useNavigate } from "react-router-dom";
import styled from "styled-components";
import tw from "twin.macro";

const BaseButton = styled.button`
  ${tw`
    inline-block
    pl-5
    pr-5
    pt-3
    pb-3
    uppercase
    outline-none
    rounded-lg
    text-white
    text-sm
    md:text-lg
    font-bold
    border-transparent
    tracking-wide
    border-2
    border-solid
    focus:outline-none
    transition-all
    duration-200
    ease-in-out
    m-1
  `}

  display: inline-block;
  transition: transform 0.1s ease-in-out;

  &:hover {
    transform: scale(0.95);
  }
`;

const OutlinedButton = styled(BaseButton)`
  ${tw`
    bg-cBlue
  `}
`;

const FilledButton = styled(BaseButton)`
  ${tw`
    border-cBlue
    text-cBlue
    bg-transparent
  `}
`;

const Button = (props) => {
  const { theme, text, phone } = props;
  const navigate = useNavigate();

  if (theme === "outlined") {
    if (phone) {
      return (
        <a href={`tel:${phone}`} style={{ textDecoration: "none" }}>
          <FilledButton>{text}</FilledButton>
        </a>
      );
    }
    return <FilledButton>{text}</FilledButton>;
  } else {
    return (
      <OutlinedButton onClick={() => navigate("/quote")}>{text}</OutlinedButton>
    );
  }
};

export default Button;
